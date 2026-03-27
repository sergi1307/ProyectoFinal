<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $code) {}

    public function build(): self
    {
        return $this->subject('Código de verificación')
                    ->html("
                    <!DOCTYPE html>
                    <html lang='es'>
                    <head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    </head>
                    <body style='margin:0; padding:0; background-color:#f8fbff; font-family: Arial, Helvetica, sans-serif;'>

                        <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f8fbff; padding: 40px 0;'>
                            <tr>
                                <td align='center'>
                                    <table width='500' cellpadding='0' cellspacing='0' style='
                                        background-color:#ffffff;
                                        border-radius:10px;
                                        box-shadow: 0px 0px 8px rgba(37, 99, 235, 0.3);
                                        overflow:hidden;
                                        max-width:500px;
                                        width:90%;
                                    '>

                                        <!-- HEADER -->
                                        <tr>
                                            <td align='center' style='
                                                background-color:#2563eb;
                                                padding: 30px 40px;
                                            '>
                                                <h1 style='color:#ffffff; margin:0; font-size:1.8em; letter-spacing:1px;'>Isaria</h1>
                                                <p style='color:rgba(255,255,255,0.8); margin:6px 0 0 0; font-size:0.85em;'>Plataforma de gestión</p>
                                            </td>
                                        </tr>

                                        <!-- BODY -->
                                        <tr>
                                            <td style='padding: 40px;'>
                                                <h2 style='color:#313131; font-size:1.3em; margin:0 0 10px 0;'>Verifica tu cuenta</h2>
                                                <p style='color:#4a4949; font-size:0.95em; line-height:1.6em; margin:0 0 30px 0;'>
                                                    Gracias por registrarte en <strong>Isaria</strong>. Para completar el proceso, introduce el siguiente código de verificación:
                                                </p>

                                                <!-- CÓDIGO -->
                                                <table width='100%' cellpadding='0' cellspacing='0'>
                                                    <tr>
                                                        <td align='center'>
                                                            <div style='
                                                                display:inline-block;
                                                                background-color:#f8fbff;
                                                                border: 2px solid #2563eb;
                                                                border-radius:10px;
                                                                padding: 20px 40px;
                                                                text-align:center;
                                                            '>
                                                                <p style='color:#4a4949; font-size:0.8em; margin:0 0 8px 0; letter-spacing:1px; text-transform:uppercase;'>Tu código es</p>
                                                                <p style='
                                                                    color:#2563eb;
                                                                    font-size:2.5em;
                                                                    font-weight:bold;
                                                                    letter-spacing:8px;
                                                                    margin:0;
                                                                '>{$this->code}</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <p style='color:#4a4949; font-size:0.85em; line-height:1.6em; margin:30px 0 0 0;'>
                                                    Este código expira en <strong>15 minutos</strong>. Si no has creado una cuenta en Isaria, puedes ignorar este email.
                                                </p>
                                            </td>
                                        </tr>

                                        <!-- FOOTER -->
                                        <tr>
                                            <td align='center' style='
                                                background-color:#f8fbff;
                                                padding: 20px 40px;
                                                border-top: 1px solid rgba(0,0,0,0.08);
                                            '>
                                                <p style='color:#4a4949; font-size:0.75em; margin:0;'>© 2026 Isaria · Todos los derechos reservados</p>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>

                    </body>
                    </html>
                ");
    }
}
