<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Daftar Makanan</title>
    <style>table{width:100%;border-collapse:collapse}th,td{padding:8px;border:1px solid #ddd}</style>
</head>
<body>
    <h2>Daftar Makanan</h2>
    <p><a href="{{ route('admin.dashboard') }}">Kembali</a> | <a href="{{ route('admin.logout') }}">Logout</a></p>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <script src="{{ asset('js/app.js') }}" defer></script>

    @if(session('status'))<div style="color:green">{{ session('status') }}</div>@endif

    <section style="display:flex; gap:18px; align-items:flex-start">
        <div style="flex:1; background:#fff; border:1px solid #eee; padding:12px; border-radius:8px;">
            <h3>Tambah Makanan</h3>
            <form method="post" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <label>Nama</label><br>
                    <input type="text" name="name" required style="width:100%; padding:8px;" />
                </div>
                <div style="margin-top:8px">
                    <label>Harga (Rp)</label><br>
                    <input type="number" name="price" required style="width:100%; padding:8px;" />
                </div>
                <div style="margin-top:8px">
                    <label>Deskripsi</label><br>
                    <textarea name="description" style="width:100%; padding:8px"></textarea>
                </div>
                <div style="margin-top:8px">
                    <label>Gambar Makanan (opsional, max 2MB)</label><br>
                    <input type="file" name="image" accept="image/*" style="width:100%; padding:8px" />
                </div>
                <div style="margin-top:12px"><button type="submit" style="padding:8px 12px; background:#2b6f4a; color:#fff; border:none; border-radius:8px">Simpan</button></div>
            </form>
        </div>

        <div style="flex:2; background:#fff; border:1px solid #eee; padding:12px; border-radius:8px;">
            <h3>Data Makanan</h3>
            <table>
                <thead><tr><th>Nama</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($items as $it)
                        <tr>
                            <td>{{ $it->name }}<div style="font-size:0.9rem;color:#666">{{ $it->description }}</div></td>
                            <td>Rp {{ number_format($it->price,0,',','.') }}</td>
                            <td>{{ $it->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>
                                <form method="post" action="{{ route('admin.menu.destroy', $it) }}" onsubmit="return confirm('Hapus item ini?')">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" style="padding:6px 8px;border-radius:6px;background:#f44336;color:#fff;border:none">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
