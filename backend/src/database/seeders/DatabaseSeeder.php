<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Incident;
use App\Models\Cart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. USUARIOS ---
        $admin = User::create([
            'name' => 'Admin Nexus',
            'email' => 'admin@nexus.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified' => true,
        ]);

        $user1 = User::create([
            'name' => 'Dani Renza',
            'email' => 'dani@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified' => true,
        ]);

        $user2 = User::create([
            'name' => 'Sara Renza',
            'email' => 'sara@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified' => true,
        ]);

        // --- 2. CATEGORÍAS ---
        $categorias = ['Cursos', 'eBooks', 'Software', 'Plantillas', 'Audios', 'Fotografías', 'Vídeos', 'Fuentes', 'Gaming', 'Extensiones', 'Servicios Web', 'Otros' ];
        $catIds = [];
        foreach ($categorias as $cat) {
            $c = Category::create(['name' => $cat]);
            $catIds[] = $c->id;
        }

        // --- 3. PRODUCTOS ---
        // Producto de prueba para el Admin
        $p1 = Product::create([
            'title' => 'Nexus UI Kit Pro',
            'description' => 'Un paquete de componentes profesionales para Vue y Figma.',
            'price' => 49.99,
            'file_url' => 'files/nexus-ui.zip',
            'image_url' => 'https://images.unsplash.com/photo-1586717791821-3f44a563eb4c',
            'user_id' => $admin->id,
            'active' => true,
        ]);
        $p1->categories()->attach([$catIds[0], $catIds[1]]);

        // Producto de prueba para Usuario 1
        $p2 = Product::create([
            'title' => 'Curso de Laravel 11',
            'description' => 'Aprende a crear APIs robustas desde cero.',
            'price' => 29.50,
            'file_url' => 'files/laravel-course.zip',
            'image_url' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12',
            'user_id' => $user1->id,
            'active' => true,
        ]);
        $p2->categories()->attach([$catIds[3]]);

        // Producto de prueba para Usuario 2
        $p3 = Product::create([
            'title' => 'Lofi Hip Hop Beats Pack',
            'description' => '50 beats sin copyright para tus streams.',
            'price' => 15.00,
            'file_url' => 'files/music-pack.zip',
            'image_url' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4',
            'user_id' => $user2->id,
            'active' => true,
        ]);
        $p3->categories()->attach([$catIds[4]]);

        // --- 4. RESEÑAS (REVIEWS) ---
        Review::create([
            'rating' => 5.0,
            'comment' => '¡Increíble UI Kit! Me ha ahorrado semanas de trabajo.',
            'user_id' => $user1->id,
            'product_id' => $p1->id,
        ]);

        Review::create([
            'rating' => 4.5,
            'comment' => 'Muy buen curso, aunque me gustaría más contenido de Testing.',
            'user_id' => $user2->id,
            'product_id' => $p2->id,
        ]);

        // --- 5. FAVORITOS ---
        DB::table('favorites')->insert([
            ['user_id' => $user1->id, 'product_id' => $p3->id, 'created_at' => now()],
            ['user_id' => $user2->id, 'product_id' => $p1->id, 'created_at' => now()],
        ]);

        // --- 6. CARRITOS ---
        $cart1 = Cart::create(['user_id' => $user1->id]);
        DB::table('cart_items')->insert([
            'cart_id' => $cart1->id_cart,
            'product_id' => $p1->id,
            'quantity' => 1,
            'added_at' => now()
        ]);

        // --- 7. INCIDENCIAS ---
        Incident::create([
            'user_id' => $user1->id,
            'subject' => 'Error en la descarga',
            'description' => 'No puedo descargar el archivo del Nexus UI Kit.',
            'status' => 'pendiente'
        ]);

        $this->command->info('Base de datos rellenada con éxito para Nexus App.');
    }
}