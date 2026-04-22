<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseReceiptMail;

class PaymentController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
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

    public function createOrder(Request $request)
    {
        $items = $request->items;
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['product']['price'] * $item['quantity'];
        }

        $comision = $subtotal * 0.05;
        $totalFinal = number_format($subtotal + $comision, 2, '.', '');

        $response = Http::withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => $totalFinal
                        ],
                        "description" => "Compra en Marketplace Proyecto Final"
                    ]
                ]
            ]);

        return response()->json($response->json());
    }

    public function captureOrder($orderId)
    {
        try {
            $token = $this->getAccessToken();
            $url = rtrim($this->baseUrl, '/') . "/v2/checkout/orders/{$orderId}/capture";

            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => "capture-{$orderId}"
                ])
                ->withBody('', 'application/json')
                ->post($url);

            if ($response->successful()) {
                $paypalData = $response->json();

                if ($paypalData['status'] !== 'COMPLETED') {
                    return response()->json(['error' => 'El pago no se ha completado en PayPal'], 400);
                }

                return DB::transaction(function () use ($paypalData) {
                    $user = auth()->user();

                    $cart = DB::table('carts')->where('user_id', $user->id)->first();

                    if (!$cart) {
                        throw new \Exception("Carrito no encontrado para el usuario.");
                    }

                    $items = DB::table('cart_items')
                        ->join('products', 'cart_items.product_id', '=', 'products.id')
                        ->where('cart_id', $cart->id_cart)
                        ->select('products.id', 'products.title', 'products.price', 'cart_items.quantity')
                        ->get();

                    if ($items->isEmpty()) {
                        throw new \Exception("El carrito está vacío.");
                    }

                    $totalPagado = $paypalData['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

                    $idOrder = DB::table('orders')->insertGetId([
                        'total' => $totalPagado,
                        'status' => 'completed',
                        'user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $details = $items->map(function ($item) use ($idOrder) {
                        return [
                            'id_order' => $idOrder,
                            'id_product' => $item->id,
                            'price' => $item->price,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    })->toArray();

                    DB::table('details')->insert($details);

                    DB::table('cart_items')->where('cart_id', $cart->id_cart)->delete();

                    Mail::to($user->email)->send(new PurchaseReceiptMail($idOrder, $totalPagado, $items));

                    return response()->json([
                        'status' => 'COMPLETED',
                        'order_id' => $idOrder,
                        'paypal_details' => $paypalData
                    ]);
                });
            }

            Log::error("Fallo de PayPal Capture:", $response->json());
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            Log::error("Error en captureOrder: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un problema al procesar el pedido: ' . $e->getMessage()], 500);
        }
    }
}
