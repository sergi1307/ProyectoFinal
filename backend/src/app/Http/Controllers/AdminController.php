<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Log as AdminLog;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

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
        return Product::whereHas('reports', function($q) {
            $q->where('resolved', false);
        })->where('active', true)
          ->with(['reports.user', 'user'])
          ->get();
    }

    public function getHistoryReports() {
        return Product::whereHas('reports', function($q) {
            $q->where('resolved', true);
        })->where('active', false)
          ->with(['reports.user', 'user'])
          ->get();
    }

    public function approveProduct($id) {
        $product = Product::findOrFail($id);

        Report::where('product_id', $id)->update(['resolved' => true]);

        AdminLog::create([
            'clase' => 'Producto',
            'accion' => 'Aprobar',
            'descripcion' => "Se ha aprobado el producto: {$product->title} (ID: {$id}). Se marcaron los reportes como resueltos.",
            'user_id' => Auth::id(),
            'payload' => ['product_id' => $id, 'title' => $product->title]
        ]);

        return response()->json(['message' => 'Producto verificado.']);
    }

    public function rejectProduct($id) {
        $product = Product::findOrFail($id);
        $product->update(['active' => false]);

        Report::where('product_id', $id)->update(['resolved' => true]);

        AdminLog::create([
            'clase' => 'Producto',
            'accion' => 'Rechazar',
            'descripcion' => "Se ha rechazado y ocultado el producto: {$product->title} (ID: {$id}).",
            'user_id' => Auth::id(),
            'payload' => ['product_id' => $id, 'title' => $product->title]
        ]);

        return response()->json(['message' => 'Producto bloqueado y ocultado del marketplace.']);
    }

    public function getMaintenanceStatus()
    {
        $status = DB::table('settings')->where('key', 'maintenance_mode')->value('value');
        return response()->json(['active' => $status === '1']);
    }

    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'active' => 'required|boolean'
        ]);

        $newValue = $request->active ? '1' : '0';

        DB::table('settings')->where('key', 'maintenance_mode')->update(['value' => $newValue, 'updated_at' => now()]);

        AdminLog::create([
            'clase' => 'Sistema',
            'accion' => $request->active ? 'Mantenimiento ON' : 'Mantenimiento OFF',
            'descripcion' => "El administrador " . Auth::user()->name . " cambió el estado del mantenimiento.",
            'user_id' => Auth::id(),
            'payload' => ['ip' => $request->ip()]
        ]);

        return response()->json(['message' => 'Estado de mantenimiento actualizado.']);
    }
}