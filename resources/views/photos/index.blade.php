@extends('layouts.app')

@section('title', 'Galería de Imágenes')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Galería de Imágenes</h1>

    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Subir nueva imagen</h2>
        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium mb-1">Título</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full border rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label for="image" class="block text-sm font-medium mb-1">Imagen (máx. 4MB)</label>
                <input type="file" name="image" id="image" accept="image/*" required
                       class="w-full text-sm border rounded-md p-1.5">
            </div>
            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md text-sm">
                    Subir imagen
                </button>
            </div>
        </form>
    </div>

    @if ($photos->isEmpty())
        <p class="text-gray-500 text-center py-10">No hay imágenes subidas todavía.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($photos as $photo)
                <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                    <img src="{{ asset('storage/' . $photo->path) }}"
                         alt="{{ $photo->title }}"
                         class="w-full h-48 object-cover">

                    <div class="p-4 flex-1 flex flex-col justify-between gap-4">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $photo->title }}</h3>

                        <form action="{{ route('photos.update', $photo) }}" method="POST" enctype="multipart/form-data" class="space-y-2 border-t pt-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $photo->title }}" required
                                   class="w-full border rounded px-2 py-1 text-xs">
                            <input type="file" name="image" accept="image/*"
                                   class="w-full text-xs border rounded p-1">
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-1.5 rounded text-xs font-medium">
                                Actualizar / Reemplazar
                            </button>
                        </form>

                        <form action="{{ route('photos.destroy', $photo) }}" method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar esta imagen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-1.5 rounded text-xs font-medium">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection