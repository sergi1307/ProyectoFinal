<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundRequestController extends Controller
{
    public function store(Request $request, $id_order)
    {
        $user = auth()->user();

        $order = DB::table('orders')->where('id_order', $id_order)->where('user_id', $user->id)->first();

        if (!$order) {
            return response()->json([
                'error' => 'Pedido no encontrado'
            ], 404);
        }

        if ($order->status != 'completed') return response()->json([
            'error' => 'No puede pedir reembolso de este pedido'
        ], 400);

        DB::table('refund_requests')->insert([
            'order_id' => $id_order,
            'user_id' => $user->id,
            'reason' => $request->reason ?? 'No especificado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('orders')->where('id_order', $id_order)->update(['status' => 'pending']);
    
        return response()->json([
            'message' => 'Solicitud de reembolso creada exitosamente'
        ], 201);
    }

    public function index()
    {
        return DB::table('refund_requests')
            ->join('orders', 'refund_requests.order_id', '=', 'orders.id_order')
            ->select('refund_requests.*', 'orders.total')
            ->orderBy('refund_requests.created_at', 'desc') // Los más nuevos arriba
            ->get();
    }
}
