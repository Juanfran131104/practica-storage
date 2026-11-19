@extends('layouts.app')

@section('title', 'Gestor de Documentos')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Gestor de Documentos PDF</h1>

    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Subir nuevo documento PDF</h2>
        <form action="{{ route('docs.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium mb-1">Título</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full border rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label for="pdf" class="block text-sm font-medium mb-1">Archivo PDF (máx. 10MB)</label>
                <input type="file" name="pdf" id="pdf" accept="application/pdf" required
                       class="w-full text-sm border rounded-md p-1.5">
            </div>
            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md text-sm">
                    Subir PDF
                </button>
            </div>
        </form>
    </div>

    @if ($docs->isEmpty())
        <p class="text-gray-500 text-center py-10">No hay documentos subidos todavía.</p>
    @else
        <div class="space-y-4">
            @foreach ($docs as $doc)
                <div class="bg-white p-4 rounded-lg shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 text-xs font-bold bg-red-100 text-red-700 rounded">PDF</span>
                        <a href="{{ asset('storage/' . $doc->path) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="font-semibold text-indigo-600 hover:underline">
                            {{ $doc->title }}
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        {{-- Formulario de reemplazo / edición --}}
                        <form action="{{ route('docs.update', $doc) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $doc->title }}" required
                                   class="border rounded px-2 py-1 text-xs">
                            <input type="file" name="pdf" accept="application/pdf"
                                   class="text-xs border rounded p-1 max-w-[180px]">
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded text-xs font-medium">
                                Reemplazar
                            </button>
                        </form>
                        <form action="{{ route('docs.destroy', $doc) }}" method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este documento?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection