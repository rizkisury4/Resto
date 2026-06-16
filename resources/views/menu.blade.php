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
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <div class="card-price">Rp 25.000</div>
                        <button type="button" class="card-add" data-name="Nasi Goreng" data-price="25000" style="padding:8px 12px; border-radius:12px; border:none; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer;">Tambah</button>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/mie-goreng-saus-tiram.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Mie Goreng</h2>
                        <p class="card-text">Mie goreng spesial dengan bumbu kecap, sayuran segar, potongan ayam, dan taburan bawang goreng yang renyah.</p>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <div class="card-price">Rp 22.000</div>
                        <button type="button" class="card-add" data-name="Mie Goreng" data-price="22000" style="padding:8px 12px; border-radius:12px; border:none; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer;">Tambah</button>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/ayam-panggang.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Nasi Ayam Goreng</h2>
                        <p class="card-text">Seporsi nasi putih hangat dengan ayam goreng kremes, sambal matah, lalapan segar, dan sayur asem.</p>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <div class="card-price">Rp 29.000</div>
                        <button type="button" class="card-add" data-name="Nasi Ayam Goreng" data-price="29000" style="padding:8px 12px; border-radius:12px; border:none; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer;">Tambah</button>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/ayam-panggang.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Ayam Bakar</h2>
                        <p class="card-text">Ayam bakar manis pedas matang sempurna, disajikan dengan nasi hangat, lalapan, dan sambal terasi khas Nusantara.</p>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <div class="card-price">Rp 32.000</div>
                        <button type="button" class="card-add" data-name="Ayam Bakar" data-price="32000" style="padding:8px 12px; border-radius:12px; border:none; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer;">Tambah</button>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/ayam-geprek.webp') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Ayam Geprek</h2>
                        <p class="card-text">Ayam goreng tepung digeprek dengan sambal cabai rawit, keju, dan kecap manis, cocok untuk pencinta rasa pedas.</p>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <div class="card-price">Rp 28.000</div>
                        <button type="button" class="card-add" data-name="Ayam Geprek" data-price="28000" style="padding:8px 12px; border-radius:12px; border:none; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer;">Tambah</button>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="image" style="background-image: url('{{ asset('images/sate-ayam.jpg') }}');"></div>
                <div class="card-content">
                    <div>
                        <h2 class="card-title">Sate Ayam</h2>
                        <p class="card-text">Sate ayam empuk dengan bumbu kacang gurih, lontong, irisan bawang merah, dan sambal kecap original.</p>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        <div class="card-price">Rp 26.000</div>
                        <button type="button" class="card-add" data-name="Sate Ayam" data-price="26000" style="padding:8px 12px; border-radius:12px; border:none; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer;">Tambah</button>
                    </div>
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

            <form id="cartForm" action="{{ route('order.checkout') }}" method="POST" style="display:grid; gap:14px;">
                @csrf

                <div id="cartDisplay" style="display:grid; gap:12px;">
                    <!-- cart rows rendered by JS -->
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-weight:700">Total: <span id="cartTotal">Rp 0</span></div>
                    <div>
                        <button type="submit" id="checkoutBtn" style="align-self:flex-start; padding:14px 24px; border:none; border-radius:14px; background:#b53f2e; color:#fff; font-weight:700; cursor:pointer; display:none;">Lanjut ke Pembayaran</button>
                        <a href="{{ route('order.index') }}" style="display:inline-block; margin-left:12px; color:#b53f2e;">Lihat daftar pesanan</a>
                    </div>
                </div>
            </form>

            <script>
                (function(){
                    const cartDisplay = document.getElementById('cartDisplay');
                    const cartTotalEl = document.getElementById('cartTotal');
                    const checkoutBtn = document.getElementById('checkoutBtn');
                    const cartForm = document.getElementById('cartForm');
                    let cart = [];

                    function formatRupiah(v){ return 'Rp ' + v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

                    function renderCart(){
                        cartDisplay.innerHTML = '';
                        let total = 0;
                        cart.forEach((it, idx)=>{
                            const row = document.createElement('div');
                            row.style.display='grid'; row.style.gridTemplateColumns='1fr 120px 80px'; row.style.gap='8px'; row.style.alignItems='center';
                            row.innerHTML = `
                                <div>
                                    <div style="font-weight:700">${it.item_name}</div>
                                    <div style="font-size:0.9rem;color:#666">${formatRupiah(it.unit_price)}</div>
                                    <div style="margin-top:6px;"><textarea data-idx="${idx}" class="note-box" rows="2" placeholder="Catatan (opsional)" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:8px">${it.notes||''}</textarea></div>
                                </div>
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <button type="button" class="dec-btn" data-idx="${idx}" style="padding:6px 8px;">-</button>
                                    <input type="number" class="qty-input" data-idx="${idx}" value="${it.quantity}" min="1" style="width:60px; text-align:center; padding:6px; border-radius:8px; border:1px solid #ddd" />
                                    <button type="button" class="inc-btn" data-idx="${idx}" style="padding:6px 8px;">+</button>
                                </div>
                                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                                    <div style="font-weight:700">${formatRupiah(it.unit_price * it.quantity)}</div>
                                    <div><button type="button" class="remove-btn" data-idx="${idx}" style="background:transparent;border:none;color:#b53f2e;cursor:pointer">Hapus</button></div>
                                </div>
                            `;
                            cartDisplay.appendChild(row);
                            total += it.unit_price * it.quantity;
                        });

                        cartTotalEl.textContent = formatRupiah(total);
                        checkoutBtn.style.display = cart.length ? 'inline-block' : 'none';

                        // attach events
                        cartDisplay.querySelectorAll('.inc-btn').forEach(b=> b.addEventListener('click', ()=>{ const i=b.dataset.idx; cart[i].quantity++; renderCart(); }));
                        cartDisplay.querySelectorAll('.dec-btn').forEach(b=> b.addEventListener('click', ()=>{ const i=b.dataset.idx; cart[i].quantity = Math.max(1, cart[i].quantity-1); renderCart(); }));
                        cartDisplay.querySelectorAll('.remove-btn').forEach(b=> b.addEventListener('click', ()=>{ const i=b.dataset.idx; cart.splice(i,1); renderCart(); }));
                        cartDisplay.querySelectorAll('.qty-input').forEach(inp=> inp.addEventListener('change', ()=>{ const i=inp.dataset.idx; cart[i].quantity = Math.max(1, parseInt(inp.value)||1); renderCart(); }));
                        cartDisplay.querySelectorAll('.note-box').forEach(nb=> nb.addEventListener('input', ()=>{ const i=nb.dataset.idx; cart[i].notes = nb.value; }));
                    }

                    // add-to-cart from card buttons
                    document.querySelectorAll('.card-add').forEach(btn=>{
                        btn.addEventListener('click', ()=>{
                            const name = btn.dataset.name;
                            const price = parseInt(btn.dataset.price);
                            const found = cart.find(it=>it.item_name===name);
                            if(found){ found.quantity++; }
                            else { cart.push({ item_name: name, unit_price: price, quantity: 1, notes: '' }); }
                            renderCart();
                        });
                    });

                    // on submit, inject hidden inputs for items
                    cartForm.addEventListener('submit', function(e){
                        // clear previous hidden inputs
                        cartForm.querySelectorAll('input[type="hidden"].cart-hidden').forEach(n=>n.remove());
                        cart.forEach((it, idx)=>{
                            const f1 = document.createElement('input'); f1.type='hidden'; f1.name=`items[${idx}][item_name]`; f1.value=it.item_name; f1.className='cart-hidden';
                            const f2 = document.createElement('input'); f2.type='hidden'; f2.name=`items[${idx}][quantity]`; f2.value=it.quantity; f2.className='cart-hidden';
                            const f3 = document.createElement('input'); f3.type='hidden'; f3.name=`items[${idx}][notes]`; f3.value=it.notes||''; f3.className='cart-hidden';
                            cartForm.appendChild(f1); cartForm.appendChild(f2); cartForm.appendChild(f3);
                        });
                    });

                    // initial empty
                    renderCart();
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
