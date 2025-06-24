<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <button id="pay-button">Bayar Sekarang</button>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">
        $('#pay-button').click(function (event) {
            event.preventDefault();
            $.ajax({
                url: '/client/payment/token',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    snap.pay(data.token, {
                        onSuccess: function(result) {
                            alert("Pembayaran sukses!");
                            console.log(result);
                        },
                        onPending: function(result) {
                            alert("Menunggu pembayaran!");
                            console.log(result);
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal!");
                            console.log(result);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
