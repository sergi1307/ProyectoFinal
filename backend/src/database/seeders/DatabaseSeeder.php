<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Incident;
use App\Models\Report;
use App\Models\Cart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        $faker = Faker::create('es_ES');

        $this->command->info('Iniciando el seeder de Nexus...');

        // --- 1. USUARIOS ---
        $this->command->info('Creando usuarios...');
        $users = [];
        
        $users[] = User::create([
            'name' => 'Admin General',
            'email' => 'admin@nexus.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'plan' => 'Ultra',
            'active' => true,
            'email_verified' => true,
            'primer_login' => false,
        ]);

        $vendedoresIds = [];
        for ($i = 0; $i < 10; $i++) { // Reducido un poco para 50 productos
            $user = User::create([
                'name' => $faker->company . ' Digital Studio',
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'user',
                'plan' => $faker->randomElement(['Pro', 'Ultra']),
                'active' => true,
                'email_verified' => true,
                'primer_login' => false,
            ]);
            $users[] = $user;
            $vendedoresIds[] = $user->id;
        }

        $compradoresIds = [];
        for ($i = 0; $i < 20; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'user',
                'plan' => 'Básico',
                'active' => $faker->boolean(90),
                'email_verified' => $faker->boolean(95),
                'primer_login' => $faker->boolean(30),
            ]);
            $users[] = $user;
            $compradoresIds[] = $user->id;
        }

        // --- 2. CATEGORÍAS ---
        $this->command->info('Creando categorías...');
        $categorias = ['Cursos', 'eBooks', 'Software', 'Plantillas', 'Audios', 'Fotografías', 'Vídeos', 'Fuentes', 'Gaming', 'Extensiones', 'Servicios Web', 'Otros'];
        $catIds = [];
        foreach ($categorias as $cat) {
            $c = Category::create(['name' => $cat]);
            $catIds[$cat] = $c->id;
        }

        // --- 3. PRODUCTOS DIGITALES (REDUCIDO A 50) ---
        $this->command->info('Creando 50 productos con imágenes aleatorias...');
        $productosIds = [];
        
        $nombresPorCat = [
            'Cursos' => ['Masterclass de Laravel', 'Curso de Marketing Digital', 'Aprende Python desde cero', 'Diseño UI/UX Avanzado', 'Taller de Node.js'],
            'eBooks' => ['Guía de Nutrición', 'Manual de Inversiones', 'Recetario Vegano', 'El Arte de la Guerra Digital', 'Ebook de Productividad'],
            'Software' => ['Gestor de Inventarios', 'Antivirus Nexus Pro', 'Editor de Código Ligero', 'Calculadora Financiera', 'SaaS de Facturación'],
            'Plantillas' => ['Landing Page Minimalista', 'Curriculum Vitae Moderno', 'Plantilla de Facturación Excel', 'Dashboard Administrativo', 'Layout para Blog'],
            'Audios' => ['Pack de Efectos de Sonido', 'Música Lo-fi para Streaming', 'Ambiente de Bosque HD', 'Podcast: Futuro Tech', 'Samples de Batería'],
            'Fotografías' => ['Texturas de Cemento', 'Paisajes de los Alpes', 'Pack de Fondos Abstractos', 'Macrofotografía de Flores', 'Cielo Estrellado HD'],
            'Vídeos' => ['Metraje de Dron 4K', 'Transiciones para Streamers', 'Fondo Animado Galaxia', 'Intro para YouTube', 'Efecto de Humo Cinematic'],
            'Fuentes' => ['Tipografía Serif Elegante', 'Fuente Script para Logos', 'Pack de Fuentes Modernas', 'Letra estilo Retro', 'Fuente Sans Compacta'],
            'Gaming' => ['Mapa para RPG Maker', 'Skin de Espada Legendaria', 'Asset de Bosque Pixel Art', 'Script de Inventario Unity', 'Pack de Sonidos 8-bit'],
            'Extensiones' => ['Bloqueador de Anuncios Pro', 'Plugin de SEO para Chrome', 'Extensión de Productividad', 'Traductor en Tiempo Real', 'Widget de Clima'],
            'Servicios Web' => ['Hosting Mensual Pro', 'Certificado SSL Anual', 'Mantenimiento de WordPress', 'Auditoría de Seguridad', 'Optimización de Velocidad'],
            'Otros' => ['Pack de Iconos SVG', 'Pinceles para Procreate', 'Manual de Usuario Genérico', 'Lista de Contactos B2B', 'Filtros para Redes Sociales']
        ];

        for ($i = 0; $i < 50; $i++) { // <-- CAMBIADO A 50
            $categoriaRandom = $faker->randomElement($categorias);
            $nombreBase = $faker->randomElement($nombresPorCat[$categoriaRandom]);
            $titulo = $nombreBase . ' ' . $faker->word; 

            $product = Product::create([
                'title' => ucfirst($titulo),
                'description' => 'Producto digital premium de la categoría ' . $categoriaRandom . '. Incluye documentación completa, archivos originales y soporte técnico de por vida.',
                'price' => $faker->randomFloat(2, 4.99, 149.99),
                'file_url' => 'files/nexus_' . $faker->uuid . '.zip',
                'file_size' => $faker->numberBetween(5242880, 524288000),
                'image_url' => 'https://picsum.photos/seed/' . $i . '/800/600', 
                'user_id' => $faker->randomElement($vendedoresIds),
                'active' => true, // Más productos activos para que se vean en la tienda
            ]);

            $product->categories()->attach($catIds[$categoriaRandom]);
            $productosIds[] = $product->id;
        }

        // --- 4. PEDIDOS (REDUCIDO PARA 50 PRODUCTOS) ---
        $this->command->info('Creando pedidos y ventas...');
        for ($i = 0; $i < 100; $i++) { // Reducido a 100 para no saturar los 50 productos
            $buyerId = $faker->randomElement($compradoresIds);
            $fechaCompra = Carbon::now()->subDays($faker->numberBetween(1, 365));
            $estado = $faker->randomElement(['completed', 'completed', 'completed', 'pending', 'cancelled']);

            $idOrder = DB::table('orders')->insertGetId([
                'total' => 0,
                'status' => $estado,
                'user_id' => $buyerId,
                'is_paid_to_seller' => ($estado === 'completed') ? $faker->boolean(80) : false,
                'created_at' => $fechaCompra,
                'updated_at' => $fechaCompra,
            ]);

            $numProductos = $faker->numberBetween(1, 2);
            $totalPedido = 0;
            $productosComprados = $faker->randomElements($productosIds, $numProductos);

            foreach ($productosComprados as $prodId) {
                $precio = Product::find($prodId)->price;
                $totalPedido += $precio;

                DB::table('details')->insert([
                    'id_order' => $idOrder,
                    'id_product' => $prodId,
                    'price' => $precio,
                    'created_at' => $fechaCompra,
                    'updated_at' => $fechaCompra,
                ]);
            }
            DB::table('orders')->where('id_order', $idOrder)->update(['total' => $totalPedido]);
        }

        // --- 5. RESEÑAS ---
        $this->command->info('Escribiendo reseñas...');
        for ($i = 0; $i < 150; $i++) { // Reducido acorde
            Review::create([
                'rating' => $faker->randomFloat(1, 3.5, 5.0),
                'comment' => $faker->randomElement(['Excelente calidad', 'Justo lo que necesitaba', 'Funciona perfectamente', 'Muy buen soporte del vendedor', 'Gran relación calidad-precio']),
                'user_id' => $faker->randomElement($compradoresIds),
                'product_id' => $faker->randomElement($productosIds),
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 300)),
            ]);
        }

        // --- 6. INCIDENCIAS ---
        $this->command->info('Generando incidencias...');
        $asuntos = ['Error al descargar archivo', 'Duda sobre licencia', 'Factura no recibida', 'El archivo ZIP está dañado', 'Problema con el pago'];
        
        for ($i = 0; $i < 20; $i++) {
            Incident::create([
                'user_id' => $faker->randomElement($users)->id,
                'subject' => $faker->randomElement($asuntos),
                'description' => 'Consulta técnica o problema reportado por el usuario sobre un producto digital recientemente adquirido.',
                'status' => $faker->randomElement(['pendiente', 'pendiente', 'resuelta']),
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 60)),
            ]);
        }

        // --- 7. REPORTES DE PRODUCTOS ---
        for ($i = 0; $i < 5; $i++) {
            Report::create([
                'product_id' => $faker->randomElement($productosIds),
                'user_id' => $faker->randomElement($compradoresIds),
                'reason' => $faker->randomElement(['Contenido Inapropiado', 'Derechos de Autor', 'Archivo Corrupto']),
                'details' => 'Reporte preventivo para moderación.',
                'resolved' => $faker->boolean(40),
            ]);
        }

        $this->command->info('✅ ¡Éxito! DB rellena: ~30 Usuarios, 12 Categorías, 50 Productos, 100 Pedidos y 20 Incidencias.');
    }
}