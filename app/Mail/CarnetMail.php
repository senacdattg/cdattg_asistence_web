<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarnetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $carnet;
    public $pdfPath;
    public $imagePath;

    public function __construct($carnet, $pdfPath, $imagePath = null)
    {
        $this->carnet = $carnet;
        $this->pdfPath = $pdfPath;
        $this->imagePath = $imagePath;
    }

    public function build()
    {
        $mail = $this->view('emails.carnet')
                     ->subject('Tu Carnet Digital')
                     ->attach($this->pdfPath);

        if ($this->imagePath && file_exists($this->imagePath)) {
            $mail->attach($this->imagePath);
        }

        return $mail;
    }
}