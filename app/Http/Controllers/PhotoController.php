<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PhotoController extends Controller
{
    public function index()
    {
        try {
            $photos = Photo::latest()->get();

            return view('photos.index', compact('photos'));
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cargar las imágenes: ' . $e->getMessage());

            return view('photos.index', ['photos' => collect()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096', // Máx 4MB
            ]);

            $path = $request->file('image')->store('photos', 'public');

            Photo::create([
                'title' => $validated['title'],
                'path'  => $path,
            ]);

            return redirect()->route('photos.index')
                ->with('success', 'Imagen subida correctamente.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->route('photos.index')
                ->with('error', 'No se pudo subir la imagen: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Photo $photo)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ]);

            $data = ['title' => $validated['title']];

            if ($request->hasFile('image')) {
                $newPath = $request->file('image')->store('photos', 'public');

                if ($photo->path && Storage::disk('public')->exists($photo->path)) {
                    Storage::disk('public')->delete($photo->path);
                }

                $data['path'] = $newPath;
            }

            $photo->update($data);

            return redirect()->route('photos.index')
                ->with('success', 'Imagen actualizada correctamente.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->route('photos.index')
                ->with('error', 'No se pudo actualizar la imagen: ' . $e->getMessage());
        }
    }

    public function destroy(Photo $photo)
    {
        try {
            if ($photo->path && Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }

            $photo->delete();

            return redirect()->route('photos.index')
                ->with('success', 'Imagen eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('photos.index')
                ->with('error', 'No se pudo eliminar la imagen: ' . $e->getMessage());
        }
    }
}