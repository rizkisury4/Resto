<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminMenuController extends Controller
{
    public function index()
    {
        $items = MenuItem::orderBy('name')->get();

        return view('admin.menu_items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // handle uploaded image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/menu_images', $filename);
            // store publicly accessible path
            $data['image_path'] = 'storage/menu_images/' . $filename;
        }

        MenuItem::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image_path' => $data['image_path'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.menu.index')->with('status', 'Menu item ditambahkan. Jangan lupa jalankan `php artisan storage:link` jika belum.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()->route('admin.menu.index')->with('status', 'Menu item dihapus.');
    }
}
