<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log as AdminLog;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $product = Product::findOrFail($id);

        $review = Review::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'product_id' => $id
        ]);

        AdminLog::create([
            'clase' => 'Reseña',
            'accion' => 'Crear',
            'descripcion' => "El usuario ha calificado con {$request->rating} estrellas el producto: '{$product->title}'.",
            'user_id' => Auth::id(),
            'payload' => [
                'product_id' => $id,
                'rating' => $request->rating,
                'tiene_comentario' => !empty($request->comment)
            ]
        ]);

        return response()->json($review, 201);
    }
}
