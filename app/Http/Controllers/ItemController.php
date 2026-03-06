<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockLog;
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
        if ($request->filled('category')) $query->where('category', $request->category);
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
        $stats      = $this->getStats();

        // Chart data — stok per kategori
        $chartData = Item::selectRaw('category, SUM(stock) as total_stock, COUNT(*) as item_count')
            ->groupBy('category')
            ->orderByDesc('total_stock')
            ->get();

        // Recent logs for dashboard (latest 5)
        $recentLogs = StockLog::with('item')->latest()->take(5)->get();

        return view('items.index', compact('items', 'categories', 'stats', 'chartData', 'recentLogs'));
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

        $item = Item::create($validated);

        StockLog::create([
            'item_id'      => $item->id,
            'item_name'    => $item->name,
            'action'       => 'created',
            'stock_before' => null,
            'stock_after'  => $item->stock,
            'stock_change' => $item->stock,
            'category'     => $item->category,
            'notes'        => "Barang baru ditambahkan dengan stok awal {$item->stock} unit.",
        ]);

        return redirect()->route('items.index')
            ->with('success', "Barang \"{$item->name}\" berhasil ditambahkan!");
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

        $stockBefore = $item->stock;
        $item->update($validated);
        $stockAfter  = $item->fresh()->stock;
        $delta       = $stockAfter - $stockBefore;

        $notes = [];
        if ($stockBefore !== $stockAfter) {
            $notes[] = "Stok berubah dari {$stockBefore} → {$stockAfter} unit (" . ($delta >= 0 ? "+{$delta}" : $delta) . ")";
        }
        if ($item->name !== $validated['name']) {
            $notes[] = "Nama diubah";
        }
        if ($item->category !== $validated['category']) {
            $notes[] = "Kategori diubah ke {$validated['category']}";
        }

        StockLog::create([
            'item_id'      => $item->id,
            'item_name'    => $item->name,
            'action'       => 'updated',
            'stock_before' => $stockBefore,
            'stock_after'  => $stockAfter,
            'stock_change' => $delta,
            'category'     => $item->category,
            'notes'        => implode('. ', $notes) ?: 'Data barang diperbarui.',
        ]);

        return redirect()->route('items.index')
            ->with('success', "Barang \"{$item->name}\" berhasil diperbarui!");
    }

    public function destroy(Item $item)
    {
        $name = $item->name;

        StockLog::create([
            'item_id'      => $item->id,
            'item_name'    => $item->name,
            'action'       => 'deleted',
            'stock_before' => $item->stock,
            'stock_after'  => null,
            'stock_change' => -$item->stock,
            'category'     => $item->category,
            'notes'        => "Barang dihapus dari inventaris (stok terakhir: {$item->stock} unit).",
        ]);

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', "Barang \"{$name}\" berhasil dihapus.");
    }

    // ── Halaman Riwayat ──────────────────────────────
    public function history(Request $request)
    {
        $query = StockLog::with('item')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(20)->withQueryString();
        return view('items.history', compact('logs'));
    }

    // ── Helpers ──────────────────────────────────────
    private function getStats(): array
    {
        return [
            'total'  => Item::count(),
            'empty'  => Item::where('stock', 0)->count(),
            'low'    => Item::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
            'medium' => Item::where('stock', '>', 10)->where('stock', '<=', 50)->count(),
            'high'   => Item::where('stock', '>', 50)->count(),
        ];
    }
}