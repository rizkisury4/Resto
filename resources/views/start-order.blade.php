<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mulai Pesan - Restoran Nusantara</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #fff7f0;
            color: #2d2a26;
            display: grid;
            place-items: center;
        }
        .kiosk {
            width: min(920px, calc(100vw - 32px));
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(320px, 420px);
            background: #fff;
            border: 1px solid rgba(181, 63, 46, 0.18);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(120, 52, 36, 0.18);
        }
        .hero {
            min-height: 560px;
            background:
                linear-gradient(180deg, rgba(45, 42, 38, 0.16), rgba(45, 42, 38, 0.54)),
                url('{{ asset('images/nasi-goreng-kencur-kemangi.jpg') }}') center/cover;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 38px;
        }
        .hero h1 {
            margin: 0;
            font-size: clamp(2.5rem, 5vw, 4.8rem);
            line-height: 0.95;
            letter-spacing: 0;
        }
        .hero p {
            max-width: 520px;
            margin: 16px 0 0;
            font-size: 1.05rem;
            line-height: 1.7;
        }
        .panel {
            padding: 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .eyebrow {
            margin: 0 0 10px;
            color: #b53f2e;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
        }
        h2 {
            margin: 0 0 8px;
            color: #2d2a26;
            font-size: 1.9rem;
            letter-spacing: 0;
        }
        .hint {
            margin: 0 0 24px;
            color: #6d6057;
            line-height: 1.6;
        }
        form {
            display: grid;
            gap: 18px;
        }
        label {
            display: grid;
            gap: 8px;
            color: #4b4039;
            font-weight: 700;
        }
        input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #d8ccc5;
            border-radius: 14px;
            padding: 14px 16px;
            font: inherit;
        }
        .service-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .service-option {
            position: relative;
        }
        .service-option input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }
        .service-card {
            min-height: 112px;
            border: 2px solid #e4d8d0;
            border-radius: 16px;
            display: grid;
            place-items: center;
            text-align: center;
            padding: 14px;
            transition: 0.2s ease;
        }
        .service-card strong {
            display: block;
            color: #2d2a26;
            font-size: 1rem;
        }
        .service-card span {
            display: block;
            margin-bottom: 8px;
            color: #b53f2e;
            font-size: 0.86rem;
            font-weight: 800;
            text-transform: uppercase;
        }
        .service-option input:checked + .service-card {
            border-color: #b53f2e;
            background: #fff3ec;
            box-shadow: 0 12px 28px rgba(181, 63, 46, 0.12);
        }
        .error {
            color: #8f2f1d;
            background: #fff1f0;
            border: 1px solid #f1c6be;
            border-radius: 14px;
            padding: 12px 14px;
        }
        button {
            border: none;
            border-radius: 16px;
            background: #b53f2e;
            color: #fff;
            font: inherit;
            font-weight: 800;
            padding: 16px 22px;
            cursor: pointer;
        }
        @media (max-width: 760px) {
            body {
                display: block;
            }
            .kiosk {
                width: 100%;
                min-height: 100vh;
                border-radius: 0;
                grid-template-columns: 1fr;
            }
            .hero {
                min-height: 280px;
                padding: 28px 24px;
            }
            .panel {
                padding: 28px 22px 34px;
            }
        }
    </style>
</head>
<body>
    <main class="kiosk">
        <section class="hero">
            <h1>Restoran Nusantara</h1>
            <p>Mulai seperti mesin self-order: masukkan nama, pilih makan di sini atau takeaway, lalu lanjut ke menu.</p>
        </section>

        <section class="panel">
            <p class="eyebrow">Mulai Pesanan</p>
            <h2>Untuk siapa pesanan ini?</h2>
            <p class="hint">Nama dan tipe layanan akan otomatis masuk ke checkout.</p>

            <form action="{{ route('order.start.submit') }}" method="POST">
                @csrf

                <label>
                    Nama
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Contoh: Budi" required autofocus />
                </label>

                <label>
                    Tipe Pesanan
                    <div class="service-options">
                        <div class="service-option">
                            <input type="radio" id="dine_in" name="service_type" value="dine_in" {{ old('service_type', 'dine_in') === 'dine_in' ? 'checked' : '' }} />
                            <div class="service-card">
                                <div>
                                    <span>Dine In</span>
                                    <strong>Makan di sini</strong>
                                </div>
                            </div>
                        </div>
                        <div class="service-option">
                            <input type="radio" id="takeaway" name="service_type" value="takeaway" {{ old('service_type') === 'takeaway' ? 'checked' : '' }} />
                            <div class="service-card">
                                <div>
                                    <span>Takeaway</span>
                                    <strong>Bawa pulang</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                @if ($errors->any())
                    <div class="error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit">Masuk ke Menu</button>
            </form>
        </section>
    </main>
</body>
</html>
