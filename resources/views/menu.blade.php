<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restoran Indonesia</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(180deg, #ffe5d4 0%, #fff9f0 100%);
            color: #2d2a26;
        }
        .page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
        }
        header {
            text-align: center;
            padding: 32px 16px 24px;
        }
        h1 {
            margin: 0;
            font-size: clamp(2.5rem, 4vw, 4.5rem);
            letter-spacing: -0.05em;
            color: #b53f2e;
        }
        p.subtitle {
            margin: 12px auto 0;
            max-width: 700px;
            font-size: 1.05rem;
            color: #5a4d45;
            line-height: 1.7;
        }
        .menu-grid {
            display: grid;
            gap: 24px;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            padding: 24px 0 40px;
        }
        .card {
            background: #ffffffcc;
            border: 1px solid rgba(181, 63, 46, 0.16);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(173, 78, 59, 0.12);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 280px;
        }
        .card .image {
            min-height: 180px;
            background-size: cover;
            background-position: center;
        }
        .card-content {
            padding: 22px 22px 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            margin: 0 0 10px;
            font-size: 1.4rem;
            color: #8f2f1d;
        }
        .card-text {
            margin: 0;
            color: #5a4d45;
            line-height: 1.65;
            flex: 1;
        }
        .card-price {
            margin-top: 18px;
            font-weight: 700;
            color: #b53f2e;
        }
        footer {
            text-align: center;
            padding: 28px 16px 12px;
            color: #6d6057;
            font-size: 0.96rem;
        }
        .tagline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(181, 63, 46, 0.12);
            color: #8f2f1d;
            font-weight: 600;
        }
        .chatbot-toggle {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 20;
            border: none;
            border-radius: 999px;
            background: #b53f2e;
            color: #fff;
            padding: 14px 18px;
            font-weight: 700;
            box-shadow: 0 14px 36px rgba(143, 47, 29, 0.28);
            cursor: pointer;
        }
        .chatbot-panel {
            position: fixed;
            right: 22px;
            bottom: 82px;
            z-index: 20;
            width: min(360px, calc(100vw - 32px));
            height: 510px;
            max-height: calc(100vh - 120px);
            background: #fff;
            border: 1px solid rgba(181, 63, 46, 0.18);
            border-radius: 18px;
            box-shadow: 0 24px 70px rgba(61, 34, 26, 0.22);
            display: none;
            overflow: hidden;
        }
        .chatbot-panel.is-open {
            display: flex;
            flex-direction: column;
        }
        .chatbot-header {
            padding: 16px 18px;
            background: #b53f2e;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .chatbot-title {
            margin: 0;
            font-size: 1rem;
            letter-spacing: 0;
        }
        .chatbot-close {
            border: none;
            background: transparent;
            color: #fff;
            font-size: 1.35rem;
            line-height: 1;
            cursor: pointer;
        }
        .chatbot-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background: #fff9f0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .chatbot-message {
            max-width: 86%;
            padding: 11px 13px;
            border-radius: 14px;
            line-height: 1.45;
            font-size: 0.94rem;
        }
        .chatbot-message.bot {
            align-self: flex-start;
            background: #fff;
            color: #4b4039;
            border: 1px solid rgba(181, 63, 46, 0.12);
        }
        .chatbot-message.user {
            align-self: flex-end;
            background: #b53f2e;
            color: #fff;
        }
        .chatbot-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 14px 0;
            background: #fff;
        }
        .chatbot-suggestion {
            border: 1px solid rgba(181, 63, 46, 0.28);
            background: #fff8f2;
            color: #8f2f1d;
            border-radius: 999px;
            padding: 8px 10px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .chatbot-form {
            display: flex;
            gap: 8px;
            padding: 12px 14px 14px;
            background: #fff;
            border-top: 1px solid rgba(181, 63, 46, 0.12);
        }
        .chatbot-input {
            flex: 1;
            min-width: 0;
            border: 1px solid #dcd6d2;
            border-radius: 12px;
            padding: 11px 12px;
            font: inherit;
        }
        .chatbot-send {
            border: none;
            border-radius: 12px;
            background: #b53f2e;
            color: #fff;
            padding: 0 14px;
            font-weight: 700;
            cursor: pointer;
        }
        .chatbot-send:disabled {
            opacity: 0.65;
            cursor: wait;
        }
        @media (max-width: 520px) {
            .chatbot-toggle {
                right: 16px;
                bottom: 16px;
            }
            .chatbot-panel {
                right: 16px;
                bottom: 72px;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <div class="tagline">Menu Makanan Indonesia </div>
            <h1>Restoran Nusantara</h1>
            <p class="subtitle">
                Halo, {{ $customerContext['customer_name'] }}. Pesanan ini untuk {{ $customerContext['service_type'] === 'dine_in' ? 'makan di sini' : 'takeaway' }}.
            </p>
            <form action="{{ route('order.session.reset') }}" method="POST" style="margin-top:16px;">
                @csrf
                <button type="submit" style="padding:10px 16px; border:1px solid rgba(181,63,46,0.24); border-radius:999px; background:#fff8f2; color:#8f2f1d; font-weight:700; cursor:pointer;">Ganti Nama / Tipe Pesanan</button>
            </form>
        </header>

        <main class="menu-grid">
            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/nasi-goreng-kencur-kemangi.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Nasi Goreng</h2>
                        <p class="card-text">Nasi goreng hangat dengan aroma bawang putih, kecap manis, irisan ayam, telur mata sapi, dan acar pedas yang segar.</p>
                    </div>
                    <div class="card-price">Rp 25.000</div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/mie-goreng-saus-tiram.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Mie Goreng</h2>
                        <p class="card-text">Mie goreng spesial dengan bumbu kecap, sayuran segar, potongan ayam, dan taburan bawang goreng yang renyah.</p>
                    </div>
                    <div class="card-price">Rp 22.000</div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/ayam-panggang.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Nasi Ayam Goreng</h2>
                        <p class="card-text">Seporsi nasi putih hangat dengan ayam goreng kremes, sambal matah, lalapan segar, dan sayur asem.</p>
                    </div>
                    <div class="card-price">Rp 29.000</div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/ayam-panggang.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Ayam Bakar</h2>
                        <p class="card-text">Ayam bakar manis pedas matang sempurna, disajikan dengan nasi hangat, lalapan, dan sambal terasi khas Nusantara.</p>
                    </div>
                    <div class="card-price">Rp 32.000</div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/ayam-geprek.webp') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Ayam Geprek</h2>
                        <p class="card-text">Ayam goreng tepung digeprek dengan sambal cabai rawit, keju, dan kecap manis, cocok untuk pencinta rasa pedas.</p>
                    </div>
                    <div class="card-price">Rp 28.000</div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/sate-ayam.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Sate Ayam</h2>
                        <p class="card-text">Sate ayam empuk dengan bumbu kacang gurih, lontong, irisan bawang merah, dan sambal kecap original.</p>
                    </div>
                    <div class="card-price">Rp 26.000</div>
                </div>
            </article>
        </main>

        <section style="background: rgba(181, 63, 46, 0.06); border: 1px solid rgba(181, 63, 46, 0.14); border-radius: 24px; padding: 24px; margin-top: 12px;">
            <h2 style="margin-top:0; color:#8f2f1d;">Pesan Makanan</h2>
            @if (session('success'))
                <div style="margin-bottom:18px; padding:14px 18px; border-radius:14px; background:#e7f7ed; color:#2f5f39; border:1px solid #b0d7b2;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('order.checkout') }}" method="POST" style="display:grid; gap:14px;">
                @csrf

                <div id="itemsContainer" style="display:grid; gap:12px;">
                    <template id="itemRowTemplate">
                        <div class="item-row" style="display:flex; gap:12px; align-items:flex-start;">
                            <div style="flex:1;">
                                <label style="display:block; font-weight:700; color:#5a4d45;">Pilih Menu
                                    <select name="items[INDEX][item_name]" required style="width:100%; padding:12px 14px; margin-top:6px; border:1px solid #dcd6d2; border-radius:12px;">
                                        <option value="Nasi Goreng">Nasi Goreng - Rp 25.000</option>
                                        <option value="Mie Goreng">Mie Goreng - Rp 22.000</option>
                                        <option value="Nasi Ayam Goreng">Nasi Ayam Goreng - Rp 29.000</option>
                                        <option value="Ayam Bakar">Ayam Bakar - Rp 32.000</option>
                                        <option value="Ayam Geprek">Ayam Geprek - Rp 28.000</option>
                                        <option value="Sate Ayam">Sate Ayam - Rp 26.000</option>
                                    </select>
                                </label>
                            </div>

                            <div style="width:120px;">
                                <label style="display:block; font-weight:700; color:#5a4d45;">Jumlah
                                    <input type="number" name="items[INDEX][quantity]" min="1" max="20" value="1" required style="width:100%; padding:12px 14px; margin-top:6px; border:1px solid #dcd6d2; border-radius:12px;" />
                                </label>
                            </div>

                            <div style="width:40px; display:flex; align-items:center;">
                                <button type="button" class="remove-item" style="margin-top:26px; background:transparent; border:none; color:#b53f2e; font-weight:700; cursor:pointer;">Hapus</button>
                            </div>
                        </div>
                        <div>
                            <label style="display:block; font-weight:700; color:#5a4d45;">Catatan (opsional)
                                <textarea name="items[INDEX][notes]" rows="2" style="width:100%; padding:12px 14px; margin-top:6px; border:1px solid #dcd6d2; border-radius:12px;"></textarea>
                            </label>
                        </div>
                    </template>
                </div>

                <div style="display:flex; gap:8px;">
                    <button type="button" id="addItemBtn" style="padding:10px 14px; border:1px solid rgba(181,63,46,0.24); border-radius:999px; background:#fff8f2; color:#8f2f1d; font-weight:700; cursor:pointer;">+ Tambah Item</button>
                    <span style="align-self:center; color:#6d6057;">Tambah beberapa jenis makanan sebelum checkout.</span>
                </div>

                @if ($errors->any())
                    <div style="color:#8f2f1d; background:#fff1f0; border:1px solid #f1c6be; border-radius:14px; padding:14px;">
                        <strong>Ada kesalahan:</strong>
                        <ul style="margin:8px 0 0 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div style="display:flex; gap:12px; align-items:center;">
                    <button type="submit" style="align-self:flex-start; padding:14px 24px; border:none; border-radius:14px; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer;">Lanjut ke Pembayaran</button>
                    <a href="{{ route('order.index') }}" style="display:inline-block; margin-top:6px; color:#b53f2e;">Lihat daftar pesanan</a>
                </div>
            </form>

            <script>
                (function(){
                    const tpl = document.getElementById('itemRowTemplate').innerHTML;
                    const container = document.getElementById('itemsContainer');
                    const addBtn = document.getElementById('addItemBtn');
                    let idx = 0;

                    function addRow(data) {
                        let html = tpl.replace(/INDEX/g, idx);
                        const wrapper = document.createElement('div');
                        wrapper.innerHTML = html;
                        // attach remove handler
                        wrapper.querySelectorAll('.remove-item').forEach(function(btn){
                            btn.addEventListener('click', function(){
                                wrapper.remove();
                            });
                        });
                        container.appendChild(wrapper);
                        idx++;
                    }

                    // initialize with one row
                    addBtn.addEventListener('click', function(){ addRow(); });
                    addRow();
                })();
            </script>
        </section>

        <footer>
            Tampilan menu makanan Indonesia sederhana untuk restoran tanpa login atau register.
        </footer>
    </div>

    <button class="chatbot-toggle" type="button" id="chatbotToggle">Chat Customer Service</button>

    <section class="chatbot-panel" id="chatbotPanel" aria-live="polite">
        <div class="chatbot-header">
            <h2 class="chatbot-title">Chat Restoran Nusantara</h2>
            <button class="chatbot-close" type="button" id="chatbotClose" aria-label="Tutup chatbot">&times;</button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="chatbot-message bot">Halo, saya bisa bantu info harga diskon, promo, delivery, dan metode pembayaran.</div>
        </div>
        <div class="chatbot-suggestions">
            <button class="chatbot-suggestion" type="button" data-question="Promo apa saja hari ini?">Promo</button>
            <button class="chatbot-suggestion" type="button" data-question="Makanan apa yang dapat diskon?">Diskon</button>
            <button class="chatbot-suggestion" type="button" data-question="Apakah bisa delivery?">Delivery</button>
            <button class="chatbot-suggestion" type="button" data-question="Bisa bayar pakai apa saja?">Pembayaran</button>
        </div>
        <form class="chatbot-form" id="chatbotForm">
            <input class="chatbot-input" id="chatbotInput" type="text" maxlength="500" placeholder="Tulis pertanyaan..." autocomplete="off" required />
            <button class="chatbot-send" id="chatbotSend" type="submit">Kirim</button>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('chatbotPanel');
            const toggle = document.getElementById('chatbotToggle');
            const close = document.getElementById('chatbotClose');
            const form = document.getElementById('chatbotForm');
            const input = document.getElementById('chatbotInput');
            const send = document.getElementById('chatbotSend');
            const messages = document.getElementById('chatbotMessages');
            const suggestions = document.querySelectorAll('.chatbot-suggestion');

            function addMessage(text, type) {
                const bubble = document.createElement('div');
                bubble.className = 'chatbot-message ' + type;
                bubble.textContent = text;
                messages.appendChild(bubble);
                messages.scrollTop = messages.scrollHeight;
                return bubble;
            }

            async function askChatbot(question) {
                const cleanQuestion = question.trim();

                if (!cleanQuestion) {
                    return;
                }

                addMessage(cleanQuestion, 'user');
                input.value = '';
                send.disabled = true;
                const loading = addMessage('Sebentar, saya cek dulu...', 'bot');

                // disable suggestion buttons while loading to prevent duplicates
                suggestions.forEach(function (b) { b.disabled = true; });

                try {
                    const response = await fetch('{{ route('chatbot.ask') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ message: cleanQuestion }),
                    });

                    if (!response.ok) {
                        const txt = await response.text();
                        loading.textContent = 'Maaf, server penghubung bermasalah (' + response.status + ').';
                        console.error('Chatbot error response:', response.status, txt);
                    } else {
                        const data = await response.json();
                        loading.textContent = data.reply || 'Maaf, belum ada jawaban.';
                    }
                } catch (error) {
                    loading.textContent = 'Maaf, chatbot sedang tidak tersambung. Silakan coba lagi.';
                    console.error('Chatbot fetch error:', error);
                } finally {
                    send.disabled = false;
                    // re-enable suggestions
                    suggestions.forEach(function (b) { b.disabled = false; });
                    input.focus();
                }
            }

            toggle.addEventListener('click', function () {
                panel.classList.toggle('is-open');
                if (panel.classList.contains('is-open')) {
                    input.focus();
                }
            });

            close.addEventListener('click', function () {
                panel.classList.remove('is-open');
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                askChatbot(input.value);
            });

            suggestions.forEach(function (button) {
                button.addEventListener('click', function () {
                    panel.classList.add('is-open');
                    askChatbot(button.dataset.question);
                });
            });
        });
    </script>
</body>
</html>
