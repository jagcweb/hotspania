<?php

namespace App\Jobs;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ffmpegPath;
    protected $videoPath;
    protected $watermarkPath;
    protected $outputVideoPath;
    protected $outputGifPath;

    // Constructor para pasar datos al trabajo
    public function __construct($ffmpegPath, $videoPath, $watermarkPath, $outputVideoPath, $outputGifPath)
    {
        $this->ffmegPath = $ffmpegPath;
        $this->videoPath = $videoPath;
        $this->watermarkPath = $watermarkPath;
        $this->outputVideoPath = $outputVideoPath;
        $this->outputGifPath = $outputGifPath;
    }

    // Este es el método que ejecutará el trabajo
    public function handle()
    {

        // Comando para procesar el video
        $command = $this->ffmpegPath . ' -i "' . $this->videoPath . '" -i "' . $this->watermarkPath . '" -filter_complex "[0:v][1:v]overlay=x=(W-w)/2:y=(H-h)/2" -c:v libx264 -preset ultrafast -crf 28 -c:a aac -strict experimental -y "' . $this->outputVideoPath . '"';
        exec($command);

        // Comando para generar el GIF
        $gifCommand = $this->ffmpegPath . ' -i "' . $this->outputVideoPath . '" -i "' . $this->watermarkPath . '" -filter_complex "[0][1]overlay=10:10,fps=10,scale=320:-1" -t 5 -y "' . $this->outputGifPath . '"';
        exec($gifCommand);

        // Aquí es donde insertamos manualmente el trabajo en la tabla `jobs`
        $job = new Job();
        $job->queue = 'default'; // La cola que estés utilizando
        $job->payload = json_encode([
            'commandName' => 'App\Jobs\ProcessVideoUpload',
            'command' => serialize($this),
        ]);
        $job->attempts = 0;
        $job->reserved_at = null;
        $job->available_at = Carbon::now(); // Usamos la fecha actual, esto es el valor que quieres que se guarde automáticamente
        $job->created_at = Carbon::now(); // Usamos la fecha actual para `created_at`
        $job->updated_at = Carbon::now(); // Usamos la fecha actual para `updated_at`

        // Guardamos el trabajo manualmente en la base de datos
        $job->save();
    }

}
