<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Sate Thaichan Oks</title>
</head>
<body>
    <h3>LAPORAN TRANSAKSI LUNAS — SATE THAICHAN OKS</h3>
    <p>Tanggal Export: {{ date('d-m-Y H:i') }}</p>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color: #E0432D; color: #FFFFFF; font-weight: bold;">
                <th>No</th>
                <th>Kode Pesanan</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Meja</th>
                <th>Detail Item</th>
                <th>Metode Bayar</th>
                <th>Total Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->customer_name ?? ($order->user->name ?? 'Pelanggan') }}</td>
                <td>{{ $order->table->table_number ?? 'Takeaway' }}</td>
                <td>
                    @foreach($order->items as $item)
                        {{ $item->quantity }}x {{ $item->menu->name ?? 'Menu' }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
                <td>{{ strtoupper($order->payment_method) }}</td>
                <td>{{ $order->total_price }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #FDF8F2;">
                <td colspan="7" align="right">TOTAL KESELURUHAN</td>
                <td>{{ $orders->sum('total_price') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>