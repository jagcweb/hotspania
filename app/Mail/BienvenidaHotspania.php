<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BienvenidaHotspania extends Mailable
{
    use Queueable, SerializesModels;

    public $correo;
    public $contrasena;
    public $loginUrl;

    public function __construct($correo, $contrasena)
    {
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->loginUrl = 'https://hotspania.es/login';
    }

    public function build()
    {
        return $this->from('consultas@hotspania.es', 'Hotspania')
                    ->subject('🎉 ¡Bienvenido a Hotspania!')
                    ->view('mail.bienvenida');
    }
}
