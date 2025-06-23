<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->id }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            background-color: #fff;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .invoice-header .logo {
            max-width: 150px;
            max-height: 150px;
        }
        .invoice-header .company-details {
            text-align: right;
        }
        .invoice-details table {
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-details td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-body table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-body th, .invoice-body td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .invoice-body th {
            background: #eee;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
        }
        .stamp {
            position: relative;
            text-align: center;
            margin-top: 20px;
        }
        .stamp-lunas {
            border: 5px solid red;
            color: red;
            font-size: 3em;
            padding: 0.2em 0.5em;
            display: inline-block;
            font-weight: bold;
            opacity: 0.5;
            transform: rotate(-15deg);
        }
        .print-button-container {
            text-align: center;
            margin: 20px;
        }

        @media print {
            .print-button-container {
                display: none;
            }
            body {
                background-color: #fff;
            }
            .invoice-box {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="print-button-container">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Invoice
        </button>
        @php
            $user = auth()->user();
            $client = auth('client')->user();
        @endphp
        @if($user && ($user->position_id == 1 || $user->position_id == 2))
            <a href="{{ route('payment-history.index') }}" class="btn btn-secondary">Kembali</a>
        @elseif($client)
            <a href="/client/tagihan" class="btn btn-secondary">Kembali</a>
        @endif
    </div>

    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                @if($setting && $setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" class="logo" alt="Logo">
                @endif
                <h4 class="mt-2">{{ $setting->store_name ?? 'Nama Toko' }}</h4>
                <p>
                    {{ $setting->address ?? 'Alamat Toko' }}<br>
                    Telp: {{ $setting->phone ?? 'Nomor Telepon' }}
                </p>
            </div>
            <div class="company-details">
                <h4>Bukti Pembayaran</h4>
                <p>
                    <strong>No. Invoice:</strong> #{{ $transaction->id }}<br>
                    <strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}<br>
                    <strong>Periode:</strong> {{ \Carbon\Carbon::create()->month($transaction->month)->format('F') }} {{ $transaction->year }}
                </p>
            </div>
        </div>

        <hr>

        <div class="invoice-details">
            <table>
                <tr>
                    <td><strong>Dibayarkan oleh:</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Nama Pelanggan:</strong></td>
                    <td>{{ $transaction->client->name }}</td>
                </tr>
                 <tr>
                    <td><strong>No. Pelanggan:</strong></td>
                    <td>{{ $transaction->client->id }}</td>
                </tr>
                <tr>
                    <td><strong>Alamat:</strong></td>
                    <td>{{ $transaction->client->address }}</td>
                </tr>
            </table>
        </div>
        
        <div class="invoice-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tagihan Internet Paket {{ $transaction->client->internet_package->name }}</td>
                        <td class="text-right">{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right"><strong>Total Pembayaran</strong></td>
                        <td class="total text-right">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="stamp">
            <div class="stamp-lunas">LUNAS</div>
        </div>

        <hr>
        <div class="text-center mt-3">
            <p>Terima kasih atas pembayaran Anda.</p>
            <small>Dicetak oleh: {{ $transaction->user->name }} pada {{ now()->format('d/m/Y H:i') }}</small>
        </div>
    </div>

</body>
</html> 