<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Producto;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImagenController extends Controller
{
    public function store(Request $request, $productoId)
    {
        if (!$request->user()->tokenCan('muebles.editar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $producto = Producto::find($productoId);
        if (!$producto) return response()->json(['mensaje' => 'Producto no encontrado'], 404);

        $validator = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($validator->fails()) return response()->json(['errores' => $validator->errors()], 422);

        // Ensure product has a gallery
        $galeria = $producto->galeria()->firstOrCreate([]);

        // Store file
        $path = $request->file('imagen')->store('imagenes', 'public');

        // Check if it's the first image to make it principal
        $isPrincipal = $galeria->imagenes()->count() === 0;

        $imagen = $galeria->imagenes()->create([
            'ruta' => $path,
            'es_principal' => $isPrincipal,
            'orden' => $galeria->imagenes()->count() + 1
        ]);

        // If it's principal, we also update the product's imagen_principal field as a shortcut
        if ($isPrincipal) {
            $producto->update(['imagen_principal' => $path]);
        }

        return response()->json($imagen, 201);
    }

    public function setPrincipal(Request $request, $id)
    {
        if (!$request->user()->tokenCan('muebles.editar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $imagen = Imagen::find($id);
        if (!$imagen) return response()->json(['mensaje' => 'Imagen no encontrada'], 404);

        $galeria = $imagen->galeria;
        
        // Remove principal from others
        $galeria->imagenes()->update(['es_principal' => false]);
        
        // Set new principal
        $imagen->update(['es_principal' => true]);

        // Shortcut on Product table
        $galeria->producto->update(['imagen_principal' => $imagen->ruta]);

        return response()->json(['mensaje' => 'Imagen establecida como principal', 'imagen' => $imagen]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->tokenCan('muebles.editar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $imagen = Imagen::find($id);
        if (!$imagen) return response()->json(['mensaje' => 'Imagen no encontrada'], 404);

        // Delete from storage
        if (Storage::disk('public')->exists($imagen->ruta)) {
            Storage::disk('public')->delete($imagen->ruta);
        }

        $wasPrincipal = $imagen->es_principal;
        $galeria = $imagen->galeria;
        $imagen->delete();

        // If we deleted the principal, let's assign another one if available
        if ($wasPrincipal) {
            $nextImage = $galeria->imagenes()->first();
            if ($nextImage) {
                $nextImage->update(['es_principal' => true]);
                $galeria->producto->update(['imagen_principal' => $nextImage->ruta]);
            } else {
                $galeria->producto->update(['imagen_principal' => null]);
            }
        }

        return response()->json(['mensaje' => 'Imagen eliminada']);
    }
}
