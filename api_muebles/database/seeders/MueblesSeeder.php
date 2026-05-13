<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;

class MueblesSeeder extends Seeder
{
    public function run(): void
    {
        // Categorías
        $sillas     = Categoria::create(['nombre' => 'Sillas',       'descripcion' => 'Sillas de todo tipo y estilo']);
        $mesas      = Categoria::create(['nombre' => 'Mesas',        'descripcion' => 'Mesas de comedor, escritorio y auxiliares']);
        $sofas      = Categoria::create(['nombre' => 'Sofás',        'descripcion' => 'Sofás y chaiselongues para el salón']);
        $camas      = Categoria::create(['nombre' => 'Camas',        'descripcion' => 'Camas y cabeceros para el dormitorio']);
        $armarios   = Categoria::create(['nombre' => 'Armarios',     'descripcion' => 'Armarios y sistemas de almacenaje']);
        $estanterias = Categoria::create(['nombre' => 'Estanterías', 'descripcion' => 'Estanterías y librerías']);
        $sillones   = Categoria::create(['nombre' => 'Sillones',     'descripcion' => 'Sillones y butacas de descanso']);
        $escritorios = Categoria::create(['nombre' => 'Escritorios', 'descripcion' => 'Escritorios y mesas de trabajo']);

        // ─── Sillas ───────────────────────────────────────────────────────────
        $p1 = Producto::create([
            'nombre'         => 'Silla Nórdica',
            'descripcion'    => 'Silla de diseño nórdico con patas de madera maciza de haya y asiento tapizado en tela de alta resistencia. Ideal para comedores y oficinas.',
            'precio'         => 45.99,
            'stock'          => 10,
            'materiales'     => 'Madera de haya y tela',
            'dimensiones'    => '50x50x80 cm',
            'color_principal' => 'Blanco',
            'destacado'      => true,
        ]);
        $p1->categorias()->attach($sillas->id);

        $p2 = Producto::create([
            'nombre'         => 'Silla Industrial Acero',
            'descripcion'    => 'Silla con estructura de acero pintado negro y asiento de madera maciza. Estilo industrial urbano perfecto para bares y comedores modernos.',
            'precio'         => 62.50,
            'stock'          => 8,
            'materiales'     => 'Acero y madera',
            'dimensiones'    => '45x48x78 cm',
            'color_principal' => 'Negro',
            'destacado'      => false,
        ]);
        $p2->categorias()->attach($sillas->id);

        $p3 = Producto::create([
            'nombre'         => 'Silla Apilable Exterior',
            'descripcion'    => 'Silla apilable de polipropileno reforzado, apta para exterior. Resistente a la intemperie y muy fácil de limpiar. Disponible en varios colores.',
            'precio'         => 29.99,
            'stock'          => 25,
            'materiales'     => 'Polipropileno',
            'dimensiones'    => '52x46x82 cm',
            'color_principal' => 'Gris',
            'destacado'      => false,
        ]);
        $p3->categorias()->attach($sillas->id);

        // ─── Mesas ────────────────────────────────────────────────────────────
        $p4 = Producto::create([
            'nombre'         => 'Mesa de Comedor Extensible',
            'descripcion'    => 'Mesa extensible de madera de roble con tapa lacada en blanco. Pasa de 120 a 180 cm en segundos. Perfecta para familias que necesitan flexibilidad.',
            'precio'         => 349.00,
            'stock'          => 5,
            'materiales'     => 'Madera de roble y MDF lacado',
            'dimensiones'    => '120-180x80x76 cm',
            'color_principal' => 'Blanco',
            'destacado'      => true,
        ]);
        $p4->categorias()->attach($mesas->id);

        $p5 = Producto::create([
            'nombre'         => 'Mesa de Centro Mármol',
            'descripcion'    => 'Elegante mesa de centro con tapa de mármol natural y base de acero dorado. Un toque de lujo para cualquier salón contemporáneo.',
            'precio'         => 189.95,
            'stock'          => 6,
            'materiales'     => 'Mármol y acero',
            'dimensiones'    => '100x60x40 cm',
            'color_principal' => 'Blanco/Dorado',
            'destacado'      => true,
        ]);
        $p5->categorias()->attach($mesas->id);

        $p6 = Producto::create([
            'nombre'         => 'Mesa Auxiliar Nido (Set 2)',
            'descripcion'    => 'Set de dos mesas nido apilables de madera de nogal con acabado natural. Versátiles y decorativas, se guardan una dentro de la otra cuando no se usan.',
            'precio'         => 99.00,
            'stock'          => 12,
            'materiales'     => 'Madera de nogal',
            'dimensiones'    => '55x40x50 cm / 45x35x45 cm',
            'color_principal' => 'Natural',
            'destacado'      => false,
        ]);
        $p6->categorias()->attach($mesas->id);

        // ─── Sofás ────────────────────────────────────────────────────────────
        $p7 = Producto::create([
            'nombre'         => 'Sofá Chesterfield 3 Plazas',
            'descripcion'    => 'Sofá Chesterfield de 3 plazas tapizado en terciopelo azul marino. Estructura de madera maciza y patas torneadas en roble natural. Botones capitoné a mano.',
            'precio'         => 799.00,
            'stock'          => 3,
            'materiales'     => 'Terciopelo y madera de roble',
            'dimensiones'    => '220x90x80 cm',
            'color_principal' => 'Azul marino',
            'destacado'      => true,
        ]);
        $p7->categorias()->attach($sofas->id);

        $p8 = Producto::create([
            'nombre'         => 'Sofá Modular Esquinero',
            'descripcion'    => 'Sofá modular en L con chaiselongue reversible. Tapizado en tela antimanchas gris marengo. Incluye cojines decorativos y función cama.',
            'precio'         => 649.00,
            'stock'          => 4,
            'materiales'     => 'Tela antimanchas y espuma HR',
            'dimensiones'    => '280x175x85 cm',
            'color_principal' => 'Gris marengo',
            'destacado'      => false,
        ]);
        $p8->categorias()->attach($sofas->id);

        $p9 = Producto::create([
            'nombre'         => 'Sofá 2 Plazas Escandinavo',
            'descripcion'    => 'Sofá de dos plazas de estilo escandinavo con patas de madera de haya y tapizado en bouclé beige. Compacto y muy cómodo.',
            'precio'         => 420.00,
            'stock'          => 7,
            'materiales'     => 'Bouclé y madera de haya',
            'dimensiones'    => '160x82x78 cm',
            'color_principal' => 'Beige',
            'destacado'      => false,
        ]);
        $p9->categorias()->attach($sofas->id);

        // ─── Camas ────────────────────────────────────────────────────────────
        $p10 = Producto::create([
            'nombre'         => 'Cama Matrimonial con Cajones',
            'descripcion'    => 'Cama de matrimonio 150x200 cm con cabecero tapizado en tela gris perla y 4 cajones de almacenaje. Base de lamas incluida.',
            'precio'         => 520.00,
            'stock'          => 4,
            'materiales'     => 'MDF y tela tapizada',
            'dimensiones'    => '160x205x100 cm (cabecero)',
            'color_principal' => 'Gris perla',
            'destacado'      => true,
        ]);
        $p10->categorias()->attach($camas->id);

        $p11 = Producto::create([
            'nombre'         => 'Cama Infantil con Tobogán',
            'descripcion'    => 'Cama infantil con tobogán y escalera, en madera maciza de pino con acabado blanco. Incluye espacio de juego bajo la cama. Para colchón 90x200 cm.',
            'precio'         => 380.00,
            'stock'          => 6,
            'materiales'     => 'Madera de pino',
            'dimensiones'    => '210x120x150 cm',
            'color_principal' => 'Blanco',
            'destacado'      => false,
        ]);
        $p11->categorias()->attach($camas->id);

        // ─── Armarios ────────────────────────────────────────────────────────
        $p12 = Producto::create([
            'nombre'         => 'Armario Ropero 3 Puertas Blanco',
            'descripcion'    => 'Armario de 3 puertas correderas con espejo central. Interior organizable con barras, baldas y cajones. Acabado blanco mate sin tiradores.',
            'precio'         => 599.00,
            'stock'          => 3,
            'materiales'     => 'MDF lacado y vidrio',
            'dimensiones'    => '240x60x220 cm',
            'color_principal' => 'Blanco',
            'destacado'      => false,
        ]);
        $p12->categorias()->attach($armarios->id);

        $p13 = Producto::create([
            'nombre'         => 'Armario Rústico Madera Maciza',
            'descripcion'    => 'Armario de madera maciza de roble con 2 puertas abatibles y herrajes metálicos estilo vintage. Interior con barra y 2 baldas fijas.',
            'precio'         => 749.00,
            'stock'          => 2,
            'materiales'     => 'Madera de roble macizo',
            'dimensiones'    => '180x55x200 cm',
            'color_principal' => 'Natural',
            'destacado'      => true,
        ]);
        $p13->categorias()->attach($armarios->id);

        // ─── Estanterías ─────────────────────────────────────────────────────
        $p14 = Producto::create([
            'nombre'         => 'Estantería Librería Industrial 5 Niveles',
            'descripcion'    => 'Estantería de 5 niveles con estructura de acero negro y baldas de madera de pino. Estilo industrial moderno. Anclaje a pared incluido.',
            'precio'         => 145.00,
            'stock'          => 15,
            'materiales'     => 'Acero y madera de pino',
            'dimensiones'    => '80x30x180 cm',
            'color_principal' => 'Negro/Natural',
            'destacado'      => false,
        ]);
        $p14->categorias()->attach($estanterias->id);

        $p15 = Producto::create([
            'nombre'         => 'Estantería Flotante Set 3',
            'descripcion'    => 'Set de 3 baldas flotantes de madera de roble con soportes ocultos. Instalación sencilla. Perfectas para decorar cualquier pared.',
            'precio'         => 59.99,
            'stock'          => 20,
            'materiales'     => 'Madera de roble',
            'dimensiones'    => '60x20x3,5 cm',
            'color_principal' => 'Natural',
            'destacado'      => false,
        ]);
        $p15->categorias()->attach($estanterias->id);

        // ─── Sillones ────────────────────────────────────────────────────────
        $p16 = Producto::create([
            'nombre'         => 'Sillón Relax Reclinable',
            'descripcion'    => 'Sillón con mecanismo reclinable manual y reposapiés integrado. Tapizado en polipiel marrón chocolate. Ideal para salones y lectores empedernidos.',
            'precio'         => 279.00,
            'stock'          => 9,
            'materiales'     => 'Polipiel y espuma HR',
            'dimensiones'    => '85x90x105 cm',
            'color_principal' => 'Marrón',
            'destacado'      => false,
        ]);
        $p16->categorias()->attach($sillones->id);

        $p17 = Producto::create([
            'nombre'         => 'Butaca Barcelona Cuero',
            'descripcion'    => 'Butaca de inspiración clásica tapizada en cuero genuino color camel. Estructura de acero inoxidable. Una pieza atemporal para espacios de distinción.',
            'precio'         => 489.00,
            'stock'          => 5,
            'materiales'     => 'Cuero genuino y acero',
            'dimensiones'    => '76x76x85 cm',
            'color_principal' => 'Camel',
            'destacado'      => true,
        ]);
        $p17->categorias()->attach($sillones->id);

        // ─── Escritorios ─────────────────────────────────────────────────────
        $p18 = Producto::create([
            'nombre'         => 'Escritorio Elevable Eléctrico',
            'descripcion'    => 'Escritorio de altura regulable eléctricamente (entre 62 y 127 cm). Superficie amplia de 140x70 cm en roble natural. Motor silencioso con memoria de posiciones.',
            'precio'         => 519.00,
            'stock'          => 7,
            'materiales'     => 'Madera de roble y acero',
            'dimensiones'    => '140x70x62-127 cm',
            'color_principal' => 'Natural/Negro',
            'destacado'      => true,
        ]);
        $p18->categorias()->attach($escritorios->id);

        $p19 = Producto::create([
            'nombre'         => 'Escritorio Compacto con Cajones',
            'descripcion'    => 'Escritorio de 3 cajones con cerradura. Tapa de melamina blanca antiarañazos. Ideal para habitaciones pequeñas o estudio.',
            'precio'         => 169.00,
            'stock'          => 11,
            'materiales'     => 'MDF melamina',
            'dimensiones'    => '110x55x76 cm',
            'color_principal' => 'Blanco',
            'destacado'      => false,
        ]);
        $p19->categorias()->attach($escritorios->id);

        // Productos con múltiples categorías
        $p20 = Producto::create([
            'nombre'         => 'Mesa Escritorio + Cajonera Set',
            'descripcion'    => 'Set completo de mesa de trabajo y cajonera a juego. Mesa de 120x60 cm con cajonera de 3 cajones sobre ruedas. Acabado en roble y blanco.',
            'precio'         => 259.00,
            'stock'          => 8,
            'materiales'     => 'MDF y chapa de roble',
            'dimensiones'    => '120x60x76 cm',
            'color_principal' => 'Roble/Blanco',
            'destacado'      => false,
        ]);
        $p20->categorias()->attach([$mesas->id, $escritorios->id]);
    }
}
