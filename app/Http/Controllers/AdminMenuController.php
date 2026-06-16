<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

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
            'image_path' => 'nullable|string|max:255',
        ]);

        MenuItem::create($data);

        return redirect()->route('admin.menu.index')->with('status', 'Menu item ditambahkan.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()->route('admin.menu.index')->with('status', 'Menu item dihapus.');
    }
}
