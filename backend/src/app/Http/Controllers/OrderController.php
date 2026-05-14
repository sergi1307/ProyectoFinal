<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Log as AdminLog;
use App\Mail\OfficialInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('details.product')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function sendOfficialInvoice($id_order)
    {
        $user = Auth::user();

        // 1. Buscamos el pedido y cargamos los productos comprados mediante la tabla pivote o relación
        $order = Order::with('details.product')->where('id_order', $id_order)->firstOrFail();

        // 2. Seguridad: Verificamos que el pedido sea de este usuario
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Acceso no autorizado a esta factura.'], 403);
        }

        // 3. Verificamos que esté completado/pagado antes de emitir factura oficial
        if ($order->status !== 'completed') {
            return response()->json(['message' => 'Solo se pueden emitir facturas de pedidos completados.'], 400);
        }

        // 4. Enviamos el correo en segundo plano (si tienes colas) o directo
        Mail::to($user->email)->send(new OfficialInvoiceMail(
            orderId: $order->id_order,
            total: $order->total,
            items: $order->details,
            user: $user
        ));

        // 5. Dejamos rastro en la auditoría
        AdminLog::create([
            'clase' => 'Pedido',
            'accion' => 'Facturar',
            'descripcion' => "El usuario {$user->name} ha solicitado la factura oficial del pedido #NX-{$order->id_order}.",
            'user_id' => $user->id,
            'payload' => ['order_id' => $order->id_order]
        ]);

        return response()->json(['message' => 'Factura enviada correctamente a tu correo.']);
    }
}
