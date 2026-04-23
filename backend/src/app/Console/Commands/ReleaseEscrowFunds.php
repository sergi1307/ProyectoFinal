<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ReleaseEscrowFunds extends Command
{
    protected $signature = 'escrow:release';
    protected $description = 'Libera los fondos en custodia mediante PayPal Payouts usando el email principal del vendedor';

    private $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = env('PAYPAL_BASE_URL', 'https://api-m.sandbox.paypal.com');
    }

    private function getAccessToken()
    {
        $response = Http::withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'))
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

        return $response->json('access_token');
    }

    public function handle()
    {
        $this->info('Iniciando proceso de liberación de fondos...');

        // Buscamos las órdenes que deben liberarse
        $orders = DB::table('orders')
            ->where('status', 'completed')
            ->where('is_paid_to_seller', false)
            ->where('release_date', '<=', now())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No hay fondos para liberar hoy.');
            return;
        }

        $token = $this->getAccessToken();

        foreach ($orders as $order) {
            // Buscamos al VENDEDOR a través de los detalles del pedido
            // (Asumimos que un pedido pertenece a un vendedor según sus productos)
            $productDetail = DB::table('details')
                ->join('products', 'details.id_product', '=', 'products.id')
                ->join('users', 'products.user_id', '=', 'users.id')
                ->where('details.id_order', $order->id_order)
                ->select('users.email', 'details.price')
                ->first();

            if (!$productDetail) {
                $this->error("No se pudo encontrar al vendedor para el pedido {$order->id_order}");
                continue;
            }

            $sellerEmail = $productDetail->email;
            $amountForSeller = number_format($order->total * 0.95, 2, '.', '');

            $this->info("Enviando {$amountForSeller}€ a {$sellerEmail}...");

            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/v1/payments/payouts", [
                    "sender_batch_header" => [
                        "sender_batch_id" => "release_" . $order->id_order . "_" . uniqid(),
                        "email_subject" => "Pago de venta liberado",
                        "email_message" => "¡Hola! El periodo de custodia ha terminado y hemos enviado tus ganancias a tu cuenta."
                    ],
                    "items" => [
                        [
                            "recipient_type" => "EMAIL",
                            "amount" => [
                                "value" => $amountForSeller,
                                "currency" => "EUR"
                            ],
                            "note" => "Liquidación automática pedido #" . $order->id_order,
                            "receiver" => $sellerEmail
                        ]
                    ]
                ]);

            if ($response->successful()) {
                DB::table('orders')
                    ->where('id_order', $order->id_order)
                    ->update(['is_paid_to_seller' => true, 'updated_at' => now()]);
                
                $this->info("¡Éxito! Pago enviado a {$sellerEmail}");
            } else {
                $this->error("Error en PayPal: " . $response->body());
            }
        }

        $this->info('Proceso finalizado.');
    }
}