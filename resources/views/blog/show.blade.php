<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        
        /* Estilos para el contenido HTML */
        .prose {
            line-height: 1.8;
        }
        .prose h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
            color: #111827;
        }
        .prose h2 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .prose h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #111827;
        }
        .prose p {
            margin-bottom: 1.25rem;
            color: #374151;
            line-height: 1.8;
        }
        .prose ul,
        .prose ol {
            margin-left: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .prose ul {
            list-style-type: disc;
        }
        .prose ol {
            list-style-type: decimal;
        }
        .prose li {
            margin-bottom: 0.75rem;
            color: #374151;
        }
        .prose a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid transparent;
            transition: all 0.2s ease;
        }
        .prose a:hover {
            color: #6d28d9;
            border-bottom-color: #7c3aed;
        }
        .prose blockquote {
            border-left: 4px solid #7c3aed;
            padding-left: 1.5rem;
            margin-left: 0;
            margin-bottom: 1.25rem;
            font-style: italic;
            color: #4b5563;
            background-color: #f9fafb;
            padding-top: 1rem;
            padding-bottom: 1rem;
            padding-right: 1.5rem;
            border-radius: 0 8px 8px 0;
        }
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 2rem 0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .prose code {
            background-color: #f3f4f6;
            color: #7c3aed;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.875rem;
        }
        .prose pre {
            background-color: #1f2937;
            color: #e5e7eb;
            padding: 1.5rem;
            border-radius: 8px;
            overflow-x: auto;
            margin-bottom: 1.25rem;
        }
        .prose pre code {
            background-color: transparent;
            color: #e5e7eb;
            padding: 0;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Tabla de contenidos */
        .toc {
            background: linear-gradient(135deg, #f0f9ff 0%, #f5f3ff 100%);
            border: 1px solid #e0e7ff;
            border-radius: 12px;
            padding: 1.5rem;
        }
        .toc-title {
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
            font-size: 1.125rem;
        }
        .toc ul {
            list-style: none;
            padding-left: 0;
        }
        .toc li {
            margin-bottom: 0.5rem;
        }
        .toc a {
            color: #7c3aed;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .toc a:hover {
            text-decoration: underline;
        }

        /* Autor */
        .author-card {
            background: linear-gradient(135deg, #f5f3ff 0%, #f0f9ff 100%);
            border: 1px solid #e0e7ff;
            border-radius: 12px;
            padding: 2rem;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <nav class="sticky top-0 z-40 backdrop-blur-md bg-white/80 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center text-gray-600 hover:text-purple-600 font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al Blog
            </a>
        </div>
    </nav>

    <!-- Article Container -->
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        
        <!-- Header Section -->
        <header class="mb-12">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <a href="{{ route('blog.index') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                    ← Blog
                </a>
            </div>

            <!-- Título principal -->
            <h1 class="font-display text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $post->title }}
            </h1>

            <!-- Metadatos -->
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6 pb-8 border-b border-gray-200">
                <!-- Fecha -->
                <div class="flex items-center text-gray-600 mb-4 md:mb-0">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <time datetime="{{ $post->published_at->toIso8601String() }}">
                        {{ $post->published_at->format('d \d\e \d\e F \d\e Y') }}
                    </time>
                </div>

                <!-- Autor -->
                @if($post->author)
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-medium">{{ $post->author->full_name ?? $post->author->email }}</span>
                    </div>
                @endif

                <!-- Tiempo de lectura estimado -->
                <div class="flex items-center text-gray-600 mt-4 md:mt-0">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ max(1, round(str_word_count(strip_tags($post->content)) / 200)) }} min lectura</span>
                </div>
            </div>

            <!-- Extracto destacado -->
            @if($post->excerpt)
                <div class="mt-8 text-xl md:text-2xl text-gray-700 font-medium italic leading-relaxed">
                    "{{ $post->excerpt }}"
                </div>
            @endif
        </header>

        <!-- Featured Image -->
        @if($post->featured_image)
            <div class="mb-12 rounded-2xl overflow-hidden shadow-xl">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-96 md:h-[500px] object-cover">
            </div>
        @endif

        <!-- Main Content -->
        <div class="prose prose-lg max-w-none mb-16">
            {!! $post->content !!}
        </div>

        <!-- Author Card -->
        @if($post->author)
            <div class="author-card mb-16">
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex-shrink-0"></div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg text-gray-900">{{ $post->author->full_name ?? $post->author->email }}</h3>
                        <p class="text-gray-600 text-sm mt-1">
                            Autor apasionado por compartir conocimiento e historias interesantes con la comunidad.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Navigation Footer -->
        <div class="border-t border-gray-200 pt-8 flex justify-center">
            <a href="{{ route('blog.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-medium rounded-lg hover:shadow-lg transition-all hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al Blog
            </a>
        </div>
    </article>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-100 mt-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <p class="text-gray-600 mb-6">¿Te gustó este artículo? Comparte con otros.</p>
                <div class="flex justify-center space-x-4 mb-8">
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 hover:bg-purple-600 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7"/></svg>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 hover:bg-purple-600 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a6 6 0 00-6 6v3H7v4h2v8h4v-8h3l1-4h-4V8a2 2 0 012-2h3z"/></svg>
                    </a>
                </div>
                <p class="text-gray-600 text-sm">© 2024 Blog. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>