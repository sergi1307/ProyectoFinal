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
use Carbon\Carbon;

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
            'active' => true,
        ]);

        $dani = User::create([
            'name' => 'Dani Renza',
            'email' => 'dani@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified' => true,
            'active' => true,
        ]);

        $sara = User::create([
            'name' => 'Sara Renza',
            'email' => 'sara@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified' => true,
            'active' => true,
        ]);

        $carlos = User::create([
            'name' => 'Carlos Dev',
            'email' => 'carlos@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified' => true,
            'active' => true,
        ]);

        // --- 2. CATEGORÍAS ---
        $categorias = ['Cursos', 'eBooks', 'Software', 'Plantillas', 'Audios', 'Fotografías', 'Vídeos', 'Fuentes', 'Gaming', 'Extensiones', 'Servicios Web', 'Otros' ];
        $catIds = [];
        foreach ($categorias as $cat) {
            $c = Category::create(['name' => $cat]);
            $catIds[] = $c->id;
        }

        // --- 3. PRODUCTOS (AHORA CON FILE_SIZE) ---
        $p1 = Product::create([
            'title' => 'Nexus UI Kit Pro',
            'description' => 'Un paquete de componentes profesionales para Vue y Figma.',
            'price' => 49.99,
            'file_url' => 'files/nexus-ui.zip',
            'file_size' => 85400000, // ~81 MB
            'image_url' => 'https://images.unsplash.com/photo-1586717791821-3f44a563eb4c',
            'user_id' => $admin->id,
            'active' => true,
        ]);
        $p1->categories()->attach([$catIds[0], $catIds[1]]);

        $p2 = Product::create([
            'title' => 'Curso de Laravel 11 Avanzado',
            'description' => 'Aprende a crear APIs robustas desde cero.',
            'price' => 29.50,
            'file_url' => 'files/laravel-course.zip',
            'file_size' => 1572864000, // ~1.5 GB
            'image_url' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12',
            'user_id' => $dani->id,
            'active' => true,
        ]);
        $p2->categories()->attach([$catIds[0]]);

        $p3 = Product::create([
            'title' => 'Lofi Hip Hop Beats Pack',
            'description' => '50 beats sin copyright para tus streams.',
            'price' => 15.00,
            'file_url' => 'files/music-pack.zip',
            'file_size' => 314572800, // ~300 MB
            'image_url' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4',
            'user_id' => $sara->id,
            'active' => true,
        ]);
        $p3->categories()->attach([$catIds[4]]);

        $p4 = Product::create([
            'title' => 'Plantilla E-commerce Vue 3',
            'description' => 'Plantilla completa con carrito, pasarela de pago y panel.',
            'price' => 89.00,
            'file_url' => 'files/ecommerce-vue.zip',
            'file_size' => 12500000, // ~12 MB
            'image_url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c',
            'user_id' => $admin->id,
            'active' => true,
        ]);
        $p4->categories()->attach([$catIds[3], $catIds[10]]);

        $p5 = Product::create([
            'title' => 'Fotografías 4K Paisajes',
            'description' => 'Pack de 100 fotos de alta calidad.',
            'price' => 10.00,
            'file_url' => 'files/fotos.zip',
            'file_size' => 4294967296, // ~4 GB
            'image_url' => 'https://images.unsplash.com/photo-1472214103451-9374bd1c798e',
            'user_id' => $carlos->id,
            'active' => true,
        ]);
        $p5->categories()->attach([$catIds[5]]);


        // --- 4. PEDIDOS Y DETALLES (NUEVO) ---
        // Uso DB::table por si los modelos Order y Detail no tienen configuradas las primary keys personalizadas (id_order, id_detail)
        
        // Pedido 1: Carlos compra a Admin (Completado)
        $idOrder1 = DB::table('orders')->insertGetId([
            'total' => 138.99,
            'status' => 'completed',
            'user_id' => $carlos->id,
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10),
        ]);
        DB::table('details')->insert([
            ['id_order' => $idOrder1, 'id_product' => $p1->id, 'price' => 49.99, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_order' => $idOrder1, 'id_product' => $p4->id, 'price' => 89.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Pedido 2: Sara compra a Dani (Completado)
        $idOrder2 = DB::table('orders')->insertGetId([
            'total' => 29.50,
            'status' => 'completed',
            'user_id' => $sara->id,
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);
        DB::table('details')->insert([
            ['id_order' => $idOrder2, 'id_product' => $p2->id, 'price' => 29.50, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Pedido 3: Dani compra a Sara (Completado)
        $idOrder3 = DB::table('orders')->insertGetId([
            'total' => 15.00,
            'status' => 'completed',
            'user_id' => $dani->id,
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2),
        ]);
        DB::table('details')->insert([
            ['id_order' => $idOrder3, 'id_product' => $p3->id, 'price' => 15.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Pedido 4: Admin compra a Carlos (Pendiente)
        $idOrder4 = DB::table('orders')->insertGetId([
            'total' => 10.00,
            'status' => 'pending',
            'user_id' => $admin->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('details')->insert([
            ['id_order' => $idOrder4, 'id_product' => $p5->id, 'price' => 10.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Pedido 5: Sara intenta comprar a Admin (Cancelado)
        $idOrder5 = DB::table('orders')->insertGetId([
            'total' => 49.99,
            'status' => 'cancelled',
            'user_id' => $sara->id,
            'created_at' => Carbon::now()->subMonth(),
            'updated_at' => Carbon::now()->subMonth(),
        ]);
        DB::table('details')->insert([
            ['id_order' => $idOrder5, 'id_product' => $p1->id, 'price' => 49.99, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);


        // --- 5. RESEÑAS (REVIEWS) ---
        Review::create([
            'rating' => 5.0,
            'comment' => '¡Increíble UI Kit! Me ha ahorrado semanas de trabajo.',
            'user_id' => $carlos->id,
            'product_id' => $p1->id,
        ]);

        Review::create([
            'rating' => 4.5,
            'comment' => 'Muy buen curso, aunque me gustaría más contenido de Testing.',
            'user_id' => $sara->id,
            'product_id' => $p2->id,
        ]);

        Review::create([
            'rating' => 5.0,
            'comment' => 'Excelentes beats, los uso en mis streams de Twitch todos los días.',
            'user_id' => $dani->id,
            'product_id' => $p3->id,
        ]);


        // --- 6. FAVORITOS ---
        DB::table('favorites')->insert([
            ['user_id' => $dani->id, 'product_id' => $p3->id, 'created_at' => Carbon::now()],
            ['user_id' => $sara->id, 'product_id' => $p1->id, 'created_at' => Carbon::now()],
            ['user_id' => $carlos->id, 'product_id' => $p2->id, 'created_at' => Carbon::now()],
        ]);

        // --- 7. CARRITOS ---
        $cart1 = Cart::create(['user_id' => $dani->id]);
        DB::table('cart_items')->insert([
            'cart_id' => $cart1->id_cart,
            'product_id' => $p1->id,
            'quantity' => 1,
            'added_at' => Carbon::now()
        ]);

        // --- 8. INCIDENCIAS ---
        Incident::create([
            'user_id' => $dani->id,
            'subject' => 'Error en la descarga',
            'description' => 'No puedo descargar el archivo del Nexus UI Kit.',
            'status' => 'pendiente'
        ]);

        Incident::create([
            'user_id' => $sara->id,
            'subject' => 'Consulta sobre licencia',
            'description' => '¿Puedo usar el UI kit en múltiples proyectos comerciales?',
            'status' => 'resuelta'
        ]);

        $this->command->info('Base de datos rellenada con éxito para Nexus App (con pedidos y file_sizes).');
    }
}