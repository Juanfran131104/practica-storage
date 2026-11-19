<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestor en la Nube')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <span class="font-bold text-lg text-indigo-600">Mi Nube Laravel</span>
            <div class="flex gap-4">
                <a href="{{ route('photos.index') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('photos.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-200' }}">
                    Galería (/photos)
                </a>
                <a href="{{ route('docs.index') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('docs.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-200' }}">
                    Documentos (/docs)
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-md bg-green-100 border border-green-400 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-md bg-red-100 border border-red-400 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-md bg-red-100 border border-red-400 text-red-800">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>