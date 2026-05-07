<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseReceiptMail;
use App\Models\Log as AdminLog;
use Illuminate\Support\Facades\Auth;

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
        
        $paypalResponse = $response->json();

        AdminLog::create([
            'clase' => 'Pago',
            'accion' => 'Crear Orden',
            'descripcion' => "El usuario inició un proceso de pago por un total de {$totalFinal}€.",
            'user_id' => Auth::id(),
            'payload' => [
                'paypal_order_id' => $paypalResponse['id'] ?? 'N/A',
                'total' => $totalFinal,
                'items_count' => count($items)
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

                return DB::transaction(function () use ($paypalData, $orderId) {
                    $user = Auth::user();

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
                    $payPalCapturedId = $paypalData['purchase_units'][0]['payments']['captures'][0]['id'];

                    $releaseDate = now()->addDays(14);

                    $idOrder = DB::table('orders')->insertGetId([
                        'total' => $totalPagado,
                        'status' => 'completed',
                        'user_id' => $user->id,
                        'paypal_order_id' => $orderId,
                        'paypal_capture_id' => $payPalCapturedId,
                        'release_date' => $releaseDate,
                        'is_paid_to_seller' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    AdminLog::create([
                        'clase' => 'Pago',
                        'accion' => 'Capturar',
                        'descripcion' => "Pago confirmado y pedido #{$idOrder} creado. Total: {$totalPagado}€.",
                        'user_id' => $user->id,
                        'payload' => [
                            'order_id' => $idOrder,
                            'paypal_capture_id' => $payPalCapturedId,
                            'total' => $totalPagado
                        ]
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
                        'release_date' => $releaseDate->format('Y-m-d H:i:s'),
                        'paypal_details' => $paypalData
                    ]);
                });
            }

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un problema al procesar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function refundOrder(Request $request, $id_order)
    {
        try {
            $id_order = (int) $id_order;

            $order = DB::table('orders')->where('id_order', $id_order)->first();

            if (!$order) {
                return response()->json([
                    'error' => 'Pedido no encontrado en la base de datos',
                    'id_enviado' => $id_order
                ], 404);
            }

            if ($order->status === 'refunded') {
                return response()->json(['error' => 'Este pedido ya ha sido reembolsado.'], 400);
            }

            if (empty($order->paypal_capture_id)) {
                return response()->json([
                    'error' => 'El pedido existe pero no tiene paypal_capture_id',
                    'fila' => $order
                ], 400);
            }

            $token = $this->getAccessToken();
            $url = rtrim($this->baseUrl, '/'). "/v2/payments/captures/{$order->paypal_capture_id}/refund";

            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => "refund-{$order->paypal_capture_id}" 
                ])
                ->post($url, [
                    "note_to_payer" => "Reembolso procesado a petición del usuario."
                ]);

            if ($response->successful()) {
                DB::transaction(function () use ($id_order) {
                    DB::table('orders')
                        ->where('id_order', $id_order)
                        ->update([
                            'status' => 'refunded',
                            'updated_at' => now()
                        ]);

                    DB::table('refund_requests')
                        ->where('order_id', $id_order)
                        ->update([
                            'status' => 'approved',
                            'updated_at' => now()
                        ]);
                });

                AdminLog::create([
                    'clase' => 'Pago',
                    'accion' => 'Reembolsar',
                    'descripcion' => "Se ha procesado un reembolso para el pedido #{$id_order} por un total de {$order->total}€.",
                    'user_id' => Auth::id(),
                    'payload' => [
                        'order_id' => $id_order,
                        'paypal_capture_id' => $order->paypal_capture_id,
                        'monto_reembolsado' => $order->total
                    ]
                ]);

                return response()->json([
                    'status' => 'REFUNDED',
                    'message' => 'El reembolso se ha procesado correctamente.'
                ], 200);
            }

            return response()->json([
                'error' => 'Paypal rechazó el reembolso'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno procesando la devolución.',
                'detalles' => $e->getMessage()
            ], 500);
        }
    }
}
