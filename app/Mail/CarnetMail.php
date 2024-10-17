<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarnetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $carnetData;

    public function __construct($carnetData)
    {
        $this->carnetData = $carnetData;
    }

    public function build()
    {
        return $this->view('carnet.carnet')
                    ->with([
                        'aprendiz' => $this->carnetData['aprendiz'],
                        'documento' => $this->carnetData['documento'],
                        'correo' => $this->carnetData['correo'],
                        'ficha' => $this->carnetData['ficha'],
                        'programa' => $this->carnetData['programa'],
                        'photo' => $this->carnetData['photo'],
                        'qr_code' => $this->carnetData['qr_code'],
                    ]);
                    
    }
}