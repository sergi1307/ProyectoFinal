<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Oficial</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif; color: #334155; font-size: 12px; }
        .header { width: 100%; margin-bottom: 30px; }
        .header td { vertical-align: top; }
        .title { font-size: 26px; font-weight: bold; color: #0f172a; }
        .subtitle { font-size: 14px; color: #0097b2; font-weight: bold; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { width: 50%; vertical-align: top; line-height: 1.5; }
        .section-title { font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background-color: #f8fafc; padding: 10px; text-align: left; font-size: 10px; text-transform: uppercase; color: #475569; border-bottom: 2px solid #e2e8f0; }
        .items-table td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; }
        .totals-table { width: 100%; }
        .totals-table td { padding: 6px 0; }
        .total-box { background-color: #f0fafd; padding: 12px; font-weight: bold; color: #0097b2; font-size: 16px; text-align: right; border-radius: 6px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td>
                <h2 style="color: #0097b2; margin: 0; font-size: 24px;">NEXUS</h2>
                <span style="font-size: 9px; color: #64748b; letter-spacing: 1.5px;">MARKETPLACE</span>
            </td>
            <td style="text-align: right;">
                <div class="title">FACTURA</div>
                <div class="subtitle"># F-NX-{{ $orderId }}</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 5px;">Fecha: {{ $fechaFactura }}</div>
            </td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 25px;">

    <table class="details-table">
        <tr>
            <td>
                <div class="section-title">Emisor</div>
                <strong>Nexus Technologies S.L.</strong><br>
                NIF: B-12345678<br>
                Av. de la Innovación, 42<br>
                28020 Madrid, España
            </td>
            <td style="text-align: right;">
                <div class="section-title">Receptor</div>
                <strong>{{ $user->name }}</strong><br>
                Email: {{ $user->email }}<br>
                Nº Cliente: NX-{{ $user->id }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th style="text-align: center; width: 12%;">Cant.</th>
                <th style="text-align: right; width: 20%;">Precio Unit.</th>
                <th style="text-align: right; width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                @php
                    $cantidad = $item->quantity ?? 1;
                    $titulo = $item->product ? $item->product->title : 'Producto eliminado';
                    $totalItem = $item->price * $cantidad;
                @endphp
                <tr>
                    <td><strong>{{ $titulo }}</strong></td>
                    <td style="text-align: center;">{{ $cantidad }}</td>
                    <td style="text-align: right;">{{ number_format($item->price, 2) }}€</td>
                    <td style="text-align: right;"><strong>{{ number_format($totalItem, 2) }}€</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div style="font-size: 9px; color: #94a3b8; line-height: 1.4;">
                    <strong>Nota Legal:</strong> Factura completa aplicable para deducción fiscal y contabilidad oficial. Operación sujeta a las reglas de localización del IVA.
                </div>
            </td>
            <td style="width: 45%;">
                <table style="width: 100%; text-align: right;">
                    <tr>
                        <td style="color: #64748b;">Base Imponible:</td>
                        <td>{{ number_format($baseImponible, 2) }}€</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">IVA (21%):</td>
                        <td>{{ number_format($cuotaIva, 2) }}€</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 15px;">
                            <div class="total-box">
                                Total Factura: {{ number_format($total, 2) }}€
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>