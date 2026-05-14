<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf; // Importamos el generador de PDF

class OfficialInvoiceMail extends Mailable implements MailableContract
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
        // 1. Preparamos los datos matemáticos para la vista
        $baseImponible = $this->total / 1.21;
        $cuotaIva = $this->total - $baseImponible;

        $datosPdf = [
            'orderId' => $this->orderId,
            'total' => $this->total,
            'items' => $this->items,
            'user' => $this->user,
            'baseImponible' => $baseImponible,
            'cuotaIva' => $cuotaIva,
            'fechaFactura' => now()->format('d/m/Y'),
        ];

        // 2. Generamos el PDF en memoria cargando la vista Blade
        $pdf = Pdf::loadView('pdf.invoice', $datosPdf);

        // 3. Diseñamos el cuerpo del correo (limpio y directo)
        $mensajeCuerpo = "
            <div style='font-family: sans-serif; color: #334155; padding: 20px;'>
                <h2 style='color: #0097b2;'>Hola, {$this->user->name}</h2>
                <p>Adjuntamos en este correo la factura oficial en formato PDF correspondiente a tu pedido <strong>#NX-{$this->orderId}</strong>.</p>
                <p>Este documento tiene validez fiscal para tu contabilidad o deducción de impuestos.</p>
                <br>
                <p style='font-size: 0.9em; color: #64748b;'>Gracias por confiar en <strong>Nexus Marketplace</strong>.</p>
            </div>
        ";

        // 4. Enviamos el correo adjuntando el PDF generado
        return $this->subject("Factura Oficial #F-NX-{$this->orderId} — Nexus Marketplace")
            ->html($mensajeCuerpo)
            ->attachData($pdf->output(), "Factura_NX_{$this->orderId}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}