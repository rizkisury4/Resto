<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Menunggu Pembayaran</title>
    <script>
        const statusUrl = '{{ route('orders.status', $order->id) }}';
        const invoiceUrl = '{{ route('orders.invoice', $order->id) }}';
        const pdfUrl = '{{ route('orders.invoice.pdf', $order->id) }}';

        async function checkStatus(){
            try {
                const res = await fetch(statusUrl);
                if (!res.ok) return;
                const data = await res.json();
                document.getElementById('status').innerText = data.status;

                        if(data.status === 'paid'){
                            // payment confirmed -> show invoice page
                            window.location = invoiceUrl;
                        }

                        if(data.status === 'completed'){
                            // order completed -> trigger PDF download and then show confirmation form before home
                            window.open(pdfUrl, '_blank');
                            // redirect to confirmation form where customer fills name and dine option
                            setTimeout(() => { window.location = '{{ route('orders.confirm', $order->id) }}'; }, 800);
                        }
            } catch (e) {
                console.error('status check failed', e);
            }
        }

        // check every 3s
        setInterval(checkStatus, 3000);
        window.addEventListener('load', () => {
            // immediate check on load
            checkStatus();
            // if already completed when page loaded, trigger immediately
            const current = document.getElementById('status').innerText.trim();
            if (current === 'completed'){
                window.open(pdfUrl, '_blank');
                setTimeout(() => { window.location = '/'; }, 1200);
            }
        });
    </script>
</head>
<body>
    <h2>Menunggu Pembayaran di Kasir</h2>
    <p><a href="/">← Home</a></p>
    <p>Order #{{ $order->id }}</p>
    <p>Status: <span id="status">{{ $order->status }}</span></p>
    <p>Silakan bayar di kasir dan tunggu konfirmasi dari petugas.</p>
</body>
</html>
