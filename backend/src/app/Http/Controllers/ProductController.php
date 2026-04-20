<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth('sanctum')->id();

        $query = Product::with(['categories', 'user'])
            ->where('active', true)
            ->where('user_id', '!=', $userId);

        if ($userId) {
            $query->withExists(['favoritedBy as is_favorite' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
        }

        return $query->latest()->get();
    }

    public function show($id)
    {
        $producto = Product::with(['user', 'categories'])->find($id);

        if (!$producto) {
            return response()->json([
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json($producto);
    }

    public function me(Request $request)
    {
        $user = Auth::user();

        $productos = Product::where('user_id', $user->id)
            ->with('categories', 'user')
            ->latest()
            ->get();
        
        return response()->json($productos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'categorias' => 'required|array',
            'file_real' => 'required|file',
            'imagen' => 'nullable|image',
        ]);

        if ($request->hasFile('file_real')) {
            $rutaArchivo = $request->file('file_real')->store('products/files', 'local');
            
            if (!$rutaArchivo) {
                return response()->json(['message' => 'El archivo llegó, pero Laravel no tiene permisos para guardarlo en el disco.'], 500);
            }
        } else {
            return response()->json(['message' => 'No se pudo leer el archivo file_real'], 422);
        }

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('products/images', 'public');
            
            if (!$rutaImagen) {
                return response()->json(['message' => 'La imagen llegó, pero Laravel no tiene permisos para guardarla.'], 500);
            }
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

    public function toggleFavorite(Request $request, Product $product)
    {
        $user = $request->user();

        $user->favorites()->toggle($product->id);

        $isFavorite = $user->favorites()->where('product_id', $product->id)->exists();

        return response()->json([
            'message' => $isFavorite ? 'Añadido a favoritos' : 'Eliminado de favoritos',
            'is_favorite' => $isFavorite,
        ]);
    }

    public function getFavorites(Request $request)
    {
        $user = $request->user();
        
        $favoritos = $user->favorites()
            ->with(['categories', 'user'])
            ->withExists(['favoritedBy as is_favorite' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest()
            ->get();

        return response()->json($favoritos);
    }
}
