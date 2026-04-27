<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordCodeMail extends Mailable
{
    use SerializesModels;

    public function __construct(public string $code) {}

    public function build(): self
    {
        return $this->subject('Código de recuperación de contraseña')
            ->view('emails.reset_password')
            ->with(['code' => $this->code]);
    }
}