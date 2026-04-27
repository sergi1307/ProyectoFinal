<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public int $orderId,
        public float $total,
        public $items,
        public $user
    ) {}

    public function build(): self
    {
        $subtotal = 0;
        $productosHtml = '';
        
        foreach ($this->items as $item) {
            $subtotal += ($item->price * $item->quantity);
            $precioFormat = number_format($item->price * $item->quantity, 2);
            
            $productosHtml .= "
                <tr>
                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; color:#475569; font-size: 0.9em;'>
                        {$item->title} <span style='color:#94a3b8; font-size:0.85em;'>(x{$item->quantity})</span>
                    </td>
                    <td align='right' style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; color:#313131; font-size: 0.9em;'>
                        {$precioFormat}€
                    </td>
                </tr>
            ";
        }

        $comision = $this->total - $subtotal;
        
        $subtotalFormat = number_format($subtotal, 2);
        $comisionFormat = number_format($comision, 2);
        $totalFormat = number_format($this->total, 2);
        $fecha = now()->format('d/m/Y');

        return $this->subject("Factura de tu pedido #NX-{$this->orderId}")
        ->html("
        <!DOCTYPE html>
        <html lang='es'>
        <head><meta charset='UTF-8'></head>
        <body style='margin:0; padding:0; background-color:#f8fafc; font-family: \"Segoe UI\", Roboto, Arial, sans-serif;'>

            <table width='100%' cellpadding='0' cellspacing='0' style='padding: 40px 0;'>
                <tr>
                    <td align='center'>
                        <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff; max-width:600px; width:100%; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden;'>

                            <tr>
                                <td style='padding: 40px 40px 20px 40px; border-bottom: 2px solid #0097b2;'>
                                    <table width='100%'>
                                        <tr>
                                            <td>
                                                <img src='" . env('APP_URL', 'http://localhost:8080') . "/logos/Logo_gr.png' alt='Nexus Logo' style='height: 60px; display: block; margin: 0 auto;'>
                                                <p style='color:#94a3b8; margin:5px 0 0 0; font-size:0.85em; text-transform:uppercase; letter-spacing:1px;'>Marketplace</p>
                                            </td>
                                            <td align='right'>
                                                <h2 style='color:#313131; margin:0; font-size:1.5em; text-transform:uppercase;'>Factura</h2>
                                                <p style='color:#64748b; margin:5px 0 0 0; font-size:0.9em;'>Nº F-NX-{$this->orderId}</p>
                                                <p style='color:#64748b; margin:2px 0 0 0; font-size:0.9em;'>Fecha: {$fecha}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style='padding: 30px 40px 10px 40px;'>
                                    <p style='color:#94a3b8; margin:0 0 5px 0; font-size:0.8em; text-transform:uppercase; font-weight:700;'>Facturado a:</p>
                                    <p style='color:#313131; margin:0 0 5px 0; font-size:1.1em; font-weight:600;'>{$this->user->name}</p>
                                    <p style='color:#64748b; margin:0; font-size:0.9em;'>{$this->user->email}</p>
                                </td>
                            </tr>

                            <tr>
                                <td style='padding: 20px 40px 40px 40px;'>
                                    <table width='100%' cellpadding='0' cellspacing='0' style='border-collapse: collapse;'>
                                        <tr>
                                            <th align='left' style='padding: 10px 0; border-bottom: 2px solid #e2e8f0; color:#64748b; font-size:0.85em; text-transform:uppercase;'>Concepto</th>
                                            <th align='right' style='padding: 10px 0; border-bottom: 2px solid #e2e8f0; color:#64748b; font-size:0.85em; text-transform:uppercase;'>Importe</th>
                                        </tr>
                                        
                                        {$productosHtml}
                                        
                                        <tr>
                                            <td align='right' style='padding: 20px 20px 5px 0; color:#64748b; font-size:0.9em;'>Subtotal:</td>
                                            <td align='right' style='padding: 20px 0 5px 0; color:#313131; font-weight:600; font-size:0.95em;'>{$subtotalFormat}€</td>
                                        </tr>
                                        <tr>
                                            <td align='right' style='padding: 5px 20px 15px 0; color:#64748b; font-size:0.9em;'>Tasas y servicio (5%):</td>
                                            <td align='right' style='padding: 5px 0 15px 0; color:#313131; font-weight:600; font-size:0.95em;'>{$comisionFormat}€</td>
                                        </tr>
                                        <tr>
                                            <td align='right' style='padding: 15px 20px 0 0; color:#0097b2; font-weight:800; font-size:1.1em; text-transform:uppercase; border-top: 2px solid #e2e8f0;'>Total Factura:</td>
                                            <td align='right' style='padding: 15px 0 0 0; color:#0097b2; font-weight:900; font-size:1.3em; border-top: 2px solid #e2e8f0;'>{$totalFormat}€</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td align='center' style='background-color:#f8fafc; padding: 20px; border-top: 1px solid #e2e8f0;'>
                                    <p style='color:#94a3b8; font-size:0.8em; margin:0;'>Este documento es una factura digital generada automáticamente por Nexus Marketplace.</p>
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