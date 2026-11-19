<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DocController extends Controller
{
    public function index()
    {
        try {
            $docs = Doc::latest()->get();

            return view('docs.index', compact('docs'));
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cargar los documentos: ' . $e->getMessage());

            return view('docs.index', ['docs' => collect()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'pdf'   => 'required|file|mimes:pdf|max:10240', // Máx 10MB
            ]);

            $path = $request->file('pdf')->store('docs', 'public');

            Doc::create([
                'title' => $validated['title'],
                'path'  => $path,
            ]);

            return redirect()->route('docs.index')
                ->with('success', 'Documento PDF subido correctamente.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->route('docs.index')
                ->with('error', 'No se pudo subir el documento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Doc $doc)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'pdf'   => 'nullable|file|mimes:pdf|max:10240',
            ]);

            $data = ['title' => $validated['title']];

            if ($request->hasFile('pdf')) {
                $newPath = $request->file('pdf')->store('docs', 'public');

                if ($doc->path && Storage::disk('public')->exists($doc->path)) {
                    Storage::disk('public')->delete($doc->path);
                }

                $data['path'] = $newPath;
            }

            $doc->update($data);

            return redirect()->route('docs.index')
                ->with('success', 'Documento actualizado correctamente.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->route('docs.index')
                ->with('error', 'No se pudo actualizar el documento: ' . $e->getMessage());
        }
    }

    public function destroy(Doc $doc)
    {
        try {
            if ($doc->path && Storage::disk('public')->exists($doc->path)) {
                Storage::disk('public')->delete($doc->path);
            }

            $doc->delete();

            return redirect()->route('docs.index')
                ->with('success', 'Documento eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('docs.index')
                ->with('error', 'No se pudo eliminar el documento: ' . $e->getMessage());
        }
    }
}