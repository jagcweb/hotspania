<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Documentation;
use Illuminate\Support\Facades\File;

class LoadDocumentation extends Command
{
    protected $signature = 'docs:load {path?}';
    protected $description = 'Carga documentación desde archivos';

    public function handle()
    {
        $path = $this->argument('path') ?: storage_path('app/documentation');
        
        if (!is_dir($path)) {
            File::makeDirectory($path, 0755, true);
            $this->info("Creada carpeta: {$path}");
            $this->info("Agrega tus archivos .txt o .md en esta carpeta y ejecuta el comando nuevamente.");
            return;
        }

        $files = File::glob($path . '/*.{txt,md}', GLOB_BRACE);
        
        if (empty($files)) {
            $this->warn('No se encontraron archivos de documentación (.txt o .md)');
            return;
        }

        $this->info('Cargando documentación...');
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            $title = pathinfo($file, PATHINFO_FILENAME);
            $content = File::get($file);
            
            Documentation::updateOrCreate(
                ['file_path' => $file],
                [
                    'title' => $title,
                    'content' => $content,
                    'category' => $this->detectCategory($title),
                    'processed_at' => now()
                ]
            );
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Documentación cargada exitosamente!');
        $this->info('Total de archivos procesados: ' . count($files));
    }

    private function detectCategory($title)
    {
        $title = strtolower($title);
        
        if (str_contains($title, 'api')) return 'API';
        if (str_contains($title, 'install')) return 'Instalación';
        if (str_contains($title, 'config')) return 'Configuración';
        if (str_contains($title, 'guide') || str_contains($title, 'tutorial')) return 'Guías';
        
        return 'General';
    }
}