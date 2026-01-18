<!DOCTYPE html>
<html>

<head>
    <title>Nota {{ $order->order_number }}</title>
    <style>
        /* CSS Khusus Cetak Struk */
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            /* Standar Printer Kasir */
            margin: 0;
            padding: 5px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        /* Hilangkan elemen header/footer browser saat print */
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<!-- onload="window.print()" artinya pas dibuka langsung muncul dialog print -->

<body onload="window.print()">

    <div class="text-center">
        <h2 style="margin:0; text-transform:uppercase;">AYAM YOGYA</h2>
        <p style="margin:5px 0;">Warung Lesehan & Catering<br>Jl. Malioboro No. 1</p>
    </div>

    <div class="line"></div>

    <div>
        No: {{ $order->order_number }}<br>
        Tgl: {{ $order->created_at->format('d/m/Y H:i') }}<br>
        Kasir: {{ Auth::user()->name }}
        Pelanggan: {{ $order->customer_name ?: 'Umum' }}<br>
        <b style="font-size: 14px;">POSISI : {{ $order->table_number ?: 'TAKEAWAY' }}</b>
    </div>

    <div class="line"></div>

    <!-- Loop Item Pesanan -->
    @foreach($order->orderItems as $item)
        <div style="margin-bottom: 2px;">
            <div>{{ $item->product->name }}</div>
            <div class="flex">
                <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                <span>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
            </div>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="flex bold" style="font-size: 14px; margin-top: 5px;">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    <div class="line"></div>

    <div class="text-center" style="margin-top: 15px;">
        Terima Kasih<br>
        <i>Selamat Menikmati</i>
    </div>

</body>

</html>