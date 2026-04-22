<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseReceiptMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public int $orderId,
        public float $total,
        public $items
    ) {}

    public function build(): self
    {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += ($item->price * $item->quantity);
        }
        $comision = $this->total - $subtotal;

        return $this->subject("Recibo de tu compra #NX-{$this->orderId}")
            ->view('emails.receipt') // <-- Aquí llamamos a la vista que acabas de crear
            ->with([
                'subtotal' => $subtotal,
                'comision' => $comision,
            ]);
    }
}