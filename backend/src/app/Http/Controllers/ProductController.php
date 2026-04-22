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
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('active', true)
            ->where('user_id', '!=', $userId);

        if ($userId) {
            $query->withExists(['favoritedBy as is_favorite' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
        }

        $productos = $query->latest()->get();

        $productos->transform(function ($producto) {
            $producto->reviews_avg_rating = $producto->reviews_avg_rating
                ? round($producto->reviews_avg_rating, 1)
                : 0;

            return $producto;
        });

        return response()->json($productos);
    }

    public function show($id)
    {
        $producto = Product::with(['user', 'categories', 'reviews.user'])->find($id);

        if (!$producto) {
            return response()->json([
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $producto->rating = round($producto->reviews()->avg('rating'), 1) ?: 0;
        $producto->reviews_count = $producto->reviews()->count();

        $user = Auth::user();

        $producto->is_favorite = $user
            ? $user->favorites()->where('product_id', $producto->id)->exists()
            : false;

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

        $rutaImagen = null;
        $tamanioArchivo = null;

        if ($request->hasFile('file_real')) {
            $archivo = $request->file('file_real');

            $tamanioArchivo = $archivo->getSize();

            $rutaArchivo = $archivo->store('products/files', 'local');

            if (!$rutaArchivo) {
                return response()->json(['message' => 'El archivo llegó, pero Laravel no tiene permisos para guardarlo en el disco.'], 500);
            }
        } else {
            return response()->json(['message' => 'No se pudo leer el archivo file_real'], 422);
        }

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
            'file_size' => $tamanioArchivo,
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

    public function destacados()
    {
        $userId = auth('sanctum')->id();

        $productos = Product::with(['categories', 'user'])
            ->where('active', true)
            ->has('reviews')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->when($userId, function ($q) use ($userId) {
                $q->withExists(['favoritedBy as is_favorite' => function ($f) use ($userId) {
                    $f->where('user_id', $userId);
                }]);
            })
            ->paginate(10);

        $productos->getCollection()->transform(function ($producto) {
            $producto->reviews_avg_rating = $producto->reviews_avg_rating
                ? round($producto->reviews_avg_rating, 1)
                : 0;

            return $producto;
        });

        return response()->json($productos);
    }

    public function tendencias()
    {
        $userId = auth('sanctum')->id();

        $productos = Product::with(['categories', 'user'])
            ->where('active', true)
            ->has('details')
            ->withAvg('reviews', 'rating')
            /* AGREGAMOS ESTA LÍNEA: */
            ->withCount('reviews')
            ->withCount(['details as sales_count' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                });
            }])
            ->having('sales_count', '>', 0)
            ->orderByDesc('sales_count')
            ->when($userId, function ($q) use ($userId) {
                $q->withExists(['favoritedBy as is_favorite' => function ($f) use ($userId) {
                    $f->where('user_id', $userId);
                }]);
            })
            ->take(10)
            ->get();

        $productos->transform(function ($producto) {
            $producto->reviews_avg_rating = $producto->reviews_avg_rating
                ? round($producto->reviews_avg_rating, 1)
                : 0;

            return $producto;
        });

        return response()->json($productos);
    }
}
