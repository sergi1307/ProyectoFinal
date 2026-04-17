<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            $url = rtrim(env('PAYPAL_BASE_URL'), '/') . "/v2/checkout/orders/{$orderId}/capture";

            // Usamos withBody y un string vacío con el content-type correcto
            // Esto asegura que PayPal reciba una petición POST limpia y sin ruido
            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    // PayPal a veces requiere esto en POST vacíos para evitar el 400
                    'PayPal-Request-Id' => uniqid() 
                ])
                ->withBody('', 'application/json') 
                ->post($url);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            // Si falla, registramos el error para verlo en el log
            Log::error("Fallo de PayPal Capture 400:", $response->json());
            
            return response()->json([
                'paypal_error' => $response->json(),
                'status' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}