<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log as AdminLog;

class SellerController extends Controller
{
    public function obtenerBalances()
    {
        $userId = Auth::id();
        $user = Auth::user();

        $pendingBalance = DB::table('details')
            ->join('orders', 'details.id_order', '=', 'orders.id_order')
            ->join('products', 'details.id_product', '=', 'products.id')
            ->where('products.user_id', $userId) 
            ->where('orders.status', 'completed')
            ->where('orders.release_date', '>', now())
            ->sum(DB::raw('details.price * 0.95'));

        $availableBalance = $user->balance;

        AdminLog::create([
            'clase' => 'Balance',
            'accion' => 'Consultar',
            'descripcion' => "El usuario {$user->name} ha consultado su estado de cuentas.",
            'user_id' => $userId,
            'payload' => [
                'disponible' => $availableBalance,
                'pendiente' => $pendingBalance
            ]
        ]);

        return response()->json([
            'pending_balance' => number_format($pendingBalance, 2, '.', ''),
            'available_balance' => $availableBalance,
            'currency' => 'EUR'
        ]);
    }
}
