<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Descubre Artículos Interesantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .badge-new {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navbar simplificado -->
    <nav class="sticky top-0 z-40 backdrop-blur-md bg-white/80 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg"></div>
                    <span class="font-display font-bold text-xl text-gray-900">Blog</span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Inicio</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Contacto</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-gray-50 via-white to-purple-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center max-w-2xl mx-auto">
                <div class="inline-block mb-4">
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                        ✨ Explora nuestras historias
                    </span>
                </div>
                <h1 class="font-display text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Historias que <span class="gradient-text">inspiran</span>
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Descubre artículos, análisis y reflexiones sobre temas que importan. Cada semana traemos contenido fresco y relevante.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        @if($posts->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay posts disponibles</h3>
                <p class="text-gray-600 mb-8">Estamos trabajando en nuevo contenido. Vuelve pronto.</p>
                <a href="/" class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all">
                    Volver al inicio
                </a>
            </div>
        @else
            <!-- Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @foreach($posts as $post)
                    <article class="card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 group">
                        <!-- Imagen con overlay -->
                        <div class="relative overflow-hidden bg-gray-100 aspect-video">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-purple-400 via-pink-500 to-blue-500 opacity-80"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6">
                            <!-- Fecha y Categoría -->
                            <div class="flex items-center justify-between mb-3">
                                <time class="text-sm font-medium text-gray-500">
                                    {{ $post->published_at->format('d \d\e M') }}
                                </time>
                                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    Artículo
                                </span>
                            </div>
                            
                            <!-- Título -->
                            <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <!-- Extracto -->
                            @if($post->excerpt)
                                <p class="text-gray-600 text-sm mb-5 line-clamp-3 leading-relaxed">
                                    {{ $post->excerpt }}
                                </p>
                            @endif
                            
                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-400 to-blue-500"></div>
                                    <span class="text-xs text-gray-600 font-medium">Leer</span>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}" 
                                   class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold text-sm group/link">
                                    Continuar
                                    <svg class="w-4 h-4 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-100 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg"></div>
                        <span class="font-display font-bold text-gray-900">Blog</span>
                    </div>
                    <p class="text-gray-600 text-sm">Historias y reflexiones que importan.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Navegación</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Inicio</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Privacidad</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Términos</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-600 hover:text-purple-600 transition-colors">Twitter</a>
                        <a href="#" class="text-gray-600 hover:text-purple-600 transition-colors">LinkedIn</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8">
                <p class="text-center text-gray-600 text-sm">© 2024 Blog. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>