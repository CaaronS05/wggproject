<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'empty':  $query->where('stock', 0); break;
                case 'low':    $query->where('stock', '>', 0)->where('stock', '<=', 10); break;
                case 'medium': $query->where('stock', '>', 10)->where('stock', '<=', 50); break;
                case 'high':   $query->where('stock', '>', 50); break;
            }
        }

        $items      = $query->latest()->paginate(10)->withQueryString();
        $categories = Item::distinct()->pluck('category');
        $stats      = [
            'total'  => Item::count(),
            'empty'  => Item::where('stock', 0)->count(),
            'low'    => Item::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
            'medium' => Item::where('stock', '>', 10)->where('stock', '<=', 50)->count(),
            'high'   => Item::where('stock', '>', 50)->count(),
        ];

        return view('items.index', compact('items', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Item::distinct()->pluck('category');
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required'     => 'Nama barang wajib diisi.',
            'stock.required'    => 'Jumlah stok wajib diisi.',
            'stock.integer'     => 'Stok harus berupa angka.',
            'stock.min'         => 'Stok tidak boleh negatif.',
            'category.required' => 'Kategori wajib dipilih.',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', "Barang \"{$validated['name']}\" berhasil ditambahkan!");
    }

    public function edit(Item $item)
    {
        $categories = Item::distinct()->pluck('category');
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required'     => 'Nama barang wajib diisi.',
            'stock.required'    => 'Jumlah stok wajib diisi.',
            'stock.integer'     => 'Stok harus berupa angka.',
            'stock.min'         => 'Stok tidak boleh negatif.',
            'category.required' => 'Kategori wajib dipilih.',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', "Barang \"{$item->name}\" berhasil diperbarui!");
    }

    public function destroy(Item $item)
    {
        $name = $item->name;
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', "Barang \"{$name}\" berhasil dihapus.");
    }
}