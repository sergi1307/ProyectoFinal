<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function ingresos()
    {
        try {
            $ingresosTotales = DB::table('orders')
                ->where('status', 'completed')
                ->sum('total');
            
            $pedidosTotales = DB::table('orders')
                ->where('status', 'completed')
                ->count();
            
            $ticketPromedio = $pedidosTotales > 0 ? $ingresosTotales / $pedidosTotales : 0;

            $pedidosRecientes = DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->select(
                    'orders.id_order as id',
                    'orders.created_at as fecha',
                    'orders.total',
                    'orders.status',
                    'users.name as usuario'
                )
                ->orderBy('orders.created_at', 'desc')
                ->limit(10)
                ->get();
            
            return response()->json([
                'ingresos_totales' => (float)$ingresosTotales,
                'pedidos_totales' => $pedidosTotales,
                'ticket_promedio' => (float)$ticketPromedio,
                'pedidos_recientes' => $pedidosRecientes
            ]);

        } catch (\Exception $e) {
            Log::error("Error en AdminController@getIngresos: " . $e->getMessage());

            return response()->json([
                'error' => 'Hubo un problema al cargar los datos estadísticos.'
            ], 500);
        }
    }

    public function getPendingReports() {
        return \App\Models\Product::whereHas('reports', function($q) {
            $q->where('resolved', false);
        })->with(['reports.user', 'user'])->get();
    }

    public function approveProduct($id) {
        \App\Models\Report::where('product_id', $id)->update(['resolved' => true]);
        return response()->json(['message' => 'Producto verificado.']);
    }

    public function rejectProduct($id) {
        $product = \App\Models\Product::findOrFail($id);
        $product->update(['active' => false]);
        \App\Models\Report::where('product_id', $id)->update(['resolved' => true]);
        return response()->json(['message' => 'Producto bloqueado y ocultado del marketplace.']);
    }
}