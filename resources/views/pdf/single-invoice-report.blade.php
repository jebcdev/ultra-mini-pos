<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Factura - {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .totals {
            text-align: right;
            margin-top: 20px;
        }

        .totals p {
            margin: 5px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Factura</h1>
        <p>Número: {{ $invoice->invoice_number }}</p>
        <p>Fecha de Emisión: {{ $invoice->issue_date->format('d/m/Y') }}</p>
        <p>Fecha de Vencimiento: {{ $invoice->due_date->format('d/m/Y') }}</p>
    </div>

    <div class="info">
        <p><strong>Cliente:</strong> {{ $invoice->client->name }}</p>
        <p><strong>Usuario:</strong> {{ $invoice->user->name }}</p>
        <p><strong>Ciudad:</strong> {{ $invoice->city->name }}</p>
        <p><strong>Dirección de Entrega:</strong> {{ $invoice->delivery_address }}</p>
        <p><strong>Notas:</strong> {{ $invoice->notes ?: 'Sin notas' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Descuento</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->discount, 2) }}</td>
                    <td>${{ number_format($item->quantity * $item->unit_price - $item->discount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Subtotal: ${{ number_format($invoice->subtotal, 2) }}</p>
        <p>Impuestos: ${{ number_format($invoice->tax_amount, 2) }}</p>
        <p>Descuentos: ${{ number_format($invoice->discount_amount, 2) }}</p>
        <p>Total: ${{ number_format($invoice->total_amount, 2) }}</p>
    </div>
</body>

</html>
