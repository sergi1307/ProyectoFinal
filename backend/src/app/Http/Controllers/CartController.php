<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * 1. VER CARRITO (Equivale a cargarCarrito() en Vue)
     */
    public function index()
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()]
        );

        $items = $cart->items()->with('product')->get();

        return response()->json($items);
    }

    /**
     * 2. AÑADIR AL CARRITO (Equivale a agregarAlCarrito() en tu ProductCard)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id_cart)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id_cart,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        $cart->touch();

        return response()->json(['message' => 'Producto agregado al carrito exitosamente'], 200);
    }

    /**
     * 3. ACTUALIZAR CANTIDAD (Equivale a los botones + y - en Vue)
     */
    public function update(Request $request, $id_cart_item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id_cart_item', $id_cart_item)
            ->whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);
        
        $cartItem->cart->touch();

        return response()->json(['message' => 'Cantidad actualizada correctamente']);
    }

    /**
     * 4. ELIMINAR DEL CARRITO (Equivale al botón de la papelera en Vue)
     */
    public function destroy($id_cart_item)
    {
        $cartItem = CartItem::where('id_cart_item', $id_cart_item)
            ->whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })->firstOrFail();

        $cartItem->cart->touch();
        $cartItem->delete();

        return response()->json(['message' => 'Producto eliminado del carrito']);
    }
}