<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <style>
        /* ======================================================== */
        /* REGLAS PARA MODO OSCURO (Apple Mail, Outlook, Gmail iOS) */
        /* ======================================================== */
        @media (prefers-color-scheme: dark) {
            .bg-exterior { background-color: #0f172a !important; }
            .bg-tarjeta { background-color: #1e293b !important; border-color: #334155 !important; }
            .bg-cabecera { background-color: #1e293b !important; border-bottom-color: #0097b2 !important; }
            
            /* Textos */
            .text-titulo { color: #f8fafc !important; }
            .text-normal { color: #cbd5e1 !important; }
            .text-muted { color: #94a3b8 !important; }
            
            /* Bordes de las tablas */
            .border-line { border-color: #334155 !important; }
            
            /* Escudo del logo en modo oscuro */
            .logo-escudo { 
                background-color: #ffffff !important; 
                padding: 10px 20px !important; 
                border-radius: 8px !important; 
                display: inline-block !important; 
            }
        }
    </style>
</head>
<body class="bg-exterior" style='margin:0; padding:0; background-color:#f4f7f9; font-family: "Segoe UI", Roboto, Arial, sans-serif;'>

    <table width='100%' cellpadding='0' cellspacing='0' class="bg-exterior" style='background-color:#f4f7f9; padding: 40px 0;'>
        <tr>
            <td align='center'>
                
                <table width='100%' cellpadding='0' cellspacing='0' class="bg-tarjeta border-line" style='background-color:#ffffff; max-width:500px; width:100%; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03);'>

                    <tr>
                        <td align='center' class="bg-cabecera border-line" style='background-color:#ffffff; padding: 40px 20px 30px 20px; border-bottom: 3px solid #0097b2;'>
                            <div class="logo-escudo" style="display: inline-block;">
                                <img src="{{ $message->embed(public_path('logos/Logo_gr.png')) }}" alt='Nexus Logo' style='height: 90px; display: block; margin: 0 auto;'>
                            </div>
                            <p class="text-muted" style='color:#64748b; margin:10px 0 0 0; font-size:0.85em; font-weight:700; text-transform:uppercase; letter-spacing:1.5px;'>Marketplace</p>
                        </td>
                    </tr>

                    <tr>
                        <td style='padding: 40px 30px 20px 30px;'>
                            <h2 class="text-titulo" style='color:#313131; font-size:1.4em; margin:0 0 10px 0;'>¡Gracias por tu compra!</h2>
                            <p class="text-normal" style='color:#6b7280; font-size:1em; line-height:1.6em; margin:0 0 30px 0;'>
                                Tu pago se ha procesado correctamente. Aquí tienes el resumen de tu pedido <strong>#NX-{{ $orderId }}</strong>.
                            </p>

                            <table width='100%' cellpadding='0' cellspacing='0' style='margin-bottom: 20px;'>
                                @foreach($items as $item)
                                <tr>
                                    <td class="text-normal border-line" style='padding: 12px 0; border-bottom: 1px solid #f1f5f9; color:#475569; font-size: 0.95em;'>
                                        {{ $item->title }} <span class="text-muted" style='color:#94a3b8; font-size:0.85em;'>(x{{ $item->quantity }})</span>
                                    </td>
                                    <td align='right' class="text-titulo border-line" style='padding: 12px 0; border-bottom: 1px solid #f1f5f9; color:#313131; font-weight:600; font-size: 0.95em;'>
                                        {{ number_format($item->price * $item->quantity, 2) }}€
                                    </td>
                                </tr>
                                @endforeach
                                
                                <tr>
                                    <td class="text-muted" style='padding: 20px 0 5px 0; color:#64748b; font-size:0.95em;'>Subtotal</td>
                                    <td align='right' class="text-muted" style='padding: 20px 0 5px 0; color:#64748b; font-size:0.95em;'>{{ number_format($subtotal, 2) }}€</td>
                                </tr>
                                <tr>
                                    <td class="text-muted border-line" style='padding: 5px 0 20px 0; color:#64748b; font-size:0.95em; border-bottom: 2px dashed #e2e8f0;'>Tasas y servicio (5%)</td>
                                    <td align='right' class="text-muted border-line" style='padding: 5px 0 20px 0; color:#64748b; font-size:0.95em; border-bottom: 2px dashed #e2e8f0;'>{{ number_format($comision, 2) }}€</td>
                                </tr>
                                <tr>
                                    <td class="text-titulo" style='padding: 20px 0 10px 0; color:#313131; font-weight:800; font-size:1.1em; text-transform:uppercase;'>Total Pagado</td>
                                    <td align='right' style='padding: 20px 0 10px 0; color:#0097b2; font-weight:900; font-size:1.5em;'>{{ number_format($total, 2) }}€</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align='center' class="bg-tarjeta border-line" style='padding: 20px 30px 40px 30px; background-color:#ffffff;'>
                            <p class="text-muted border-line" style='color:#94a3b8; font-size:0.8em; margin:0; border-top: 1px solid #f1f5f9; padding-top: 20px;'>© 2026 Isaria Marketplace · Todos los derechos reservados</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>