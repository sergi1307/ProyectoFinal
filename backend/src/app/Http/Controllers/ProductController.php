<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function me(Request $request)
    {
        $user = Auth::user();

        $productos = Product::where('user_id', $user->id)
            ->with('categories')
            ->latest()
            ->get();
        
        return response()->json($productos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric',
            'categorias' => 'required|array',
            'file_real' => 'required|file',
            'imagen' => 'nullable|image',
        ]);

        if ($request->hasFile('file_real')) {
            $rutaArchivo = $request->file('file_real')->store('products/files', 'local');
        } else {
            return response()->json(['message' => 'No se pudo leer el archivo file_real'], 422);
        }

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('products/images', 'public');
        }

        $product = Product::create([
            'title' => $request->nombre,
            'description' => $request->descripcion,
            'price' => $request->precio,
            'file_url' => $rutaArchivo,
            'image_url' => $rutaImagen,
            'active' => true,
            'user_id' => Auth::id(),
        ]);

        $product->categories()->attach($request->categorias);

        return response()->json(['message' => '¡Éxito!'], 201);
    }
}
