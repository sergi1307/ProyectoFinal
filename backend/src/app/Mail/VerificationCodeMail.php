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
        <body style='margin:0; padding:0; background-color:#ffffff; font-family: \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>

            <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;'>
                <tr>
                    <td align='center'>
                        <table width='100%' cellpadding='0' cellspacing='0' style='
                            background-color:#ffffff;
                            max-width:500px;
                            width:100%;
                        '>

                            <tr>
                                <td align='center' style='
                                    background-color:#0097b2;
                                    padding: 35px 20px;
                                '>
                                    <h1 style='color:#ffffff; margin:0; font-size:2.2em; font-weight:800; letter-spacing:0.5px;'>Nexus</h1>
                                    <p style='color:#5ce1e6; margin:6px 0 0 0; font-size:0.95em; font-weight:600; text-transform:uppercase; letter-spacing:1px;'>Marketplace</p>
                                </td>
                            </tr>

                            <tr>
                                <td style='padding: 40px 20px;'>
                                    <h2 style='color:#313131; font-size:1.4em; margin:0 0 10px 0;'>Verifica tu cuenta</h2>
                                    <p style='color:#6b7280; font-size:1em; line-height:1.6em; margin:0 0 30px 0;'>
                                        Gracias por registrarte en <strong>Nexus</strong>. Para completar el proceso, introduce el siguiente código de verificación:
                                    </p>

                                    <table width='100%' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td align='center'>
                                                <div style='
                                                    display:inline-block;
                                                    background-color:#f8fbff;
                                                    border: 2px solid #0097b2;
                                                    border-radius:8px;
                                                    padding: 20px 40px;
                                                    text-align:center;
                                                '>
                                                    <p style='color:#6b7280; font-size:0.85em; margin:0 0 8px 0; letter-spacing:1px; text-transform:uppercase; font-weight:600;'>Tu código es</p>
                                                    <p style='
                                                        color:#0097b2;
                                                        font-size:2.5em;
                                                        font-weight:800;
                                                        letter-spacing:8px;
                                                        margin:0;
                                                    '>{$this->code}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <p style='color:#6b7280; font-size:0.9em; line-height:1.6em; margin:30px 0 0 0;'>
                                        Este código de 6 dígitos expira en <strong>15 minutos</strong>. Si no has intentado crear una cuenta en Nexus, puedes ignorar o eliminar este email con tranquilidad.
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <td align='center' style='
                                    background-color:#ffffff;
                                    padding: 20px 20px 40px 20px;
                                '>
                                    <p style='color:#6b7280; font-size:0.8em; margin:0; border-top: 1px solid #f8fbff; padding-top: 20px;'>© 2026 Nexus Marketplace · Todos los derechos reservados</p>
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
