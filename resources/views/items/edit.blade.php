@extends('layouts.app')

@section('title', 'Edit Barang — SeasonStock')

@php
function getCatEmoji($cat) {
    $map = [
        'elektronik' => '💻', 'alat tulis' => '✏️', 'makanan' => '🍱',
        'minuman' => '🥤', 'pakaian' => '👕', 'peralatan rumah' => '🏠',
        'obat-obatan' => '💊', 'buku' => '📚',
    ];
    return $map[strtolower($cat)] ?? '📦';
}
@endphp

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm font-body mb-6">
    <a href="{{ route('items.index') }}" class="text-bark-500 hover:text-bark-700 transition-colors">Inventory</a>
    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-500">Edit Barang</span>
</nav>

<div class="max-w-2xl">
    {{-- Header --}}
    <div class="mb-8">
        <p class="text-sm text-bark-500 font-body uppercase tracking-widest mb-1">Edit Data</p>
        <h1 class="font-display text-3xl text-bark-900 font-semibold">Perbarui Barang</h1>
        <p class="mt-1.5 text-sm font-body">
            <span class="text-slate-400">Mengedit:</span>
            <span class="text-bark-700 font-medium">{{ $item->name }}</span>
        </p>
    </div>

    {{-- Current Stock Info --}}
    <div class="card-season rounded-2xl p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
            @if($item->stock_status === 'empty') bg-red-50
            @elseif($item->stock_status === 'low') bg-amber-50
            @elseif($item->stock_status === 'medium') bg-blue-50
            @else bg-green-50
            @endif">
            <svg class="w-6 h-6
                @if($item->stock_status === 'empty') text-red-500
                @elseif($item->stock_status === 'low') text-amber-500
                @elseif($item->stock_status === 'medium') text-blue-500
                @else text-green-500
                @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-slate-400 font-body">Stok Saat Ini</p>
            <div class="flex items-center gap-3 mt-0.5">
                <p class="font-display text-2xl font-semibold
                    @if($item->stock_status === 'empty') text-red-600
                    @elseif($item->stock_status === 'low') text-amber-600
                    @elseif($item->stock_status === 'medium') text-blue-600
                    @else text-green-600
                    @endif">
                    {{ number_format($item->stock) }} unit
                </p>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold font-body
                    @if($item->stock_status === 'empty') badge-empty
                    @elseif($item->stock_status === 'low') badge-low
                    @elseif($item->stock_status === 'medium') badge-medium
                    @else badge-high
                    @endif">
                    {{ $item->stock_label }}
                </span>
            </div>
        </div>
        <div class="text-right text-xs text-slate-400 font-body hidden sm:block">
            <p>Kategori</p>
            <p class="font-medium text-bark-600 mt-0.5">{{ $item->category }}</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card-season rounded-2xl p-8">
        <form method="POST" action="{{ route('items.update', $item) }}" id="edit-form">
            @csrf
            @method('PUT')

            {{-- Nama Barang --}}
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-slate-700 font-body mb-2">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}"
                       placeholder="Nama barang"
                       class="input-season w-full px-4 py-3 rounded-xl text-sm font-body text-slate-800 placeholder-slate-400 @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600 font-body flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Jumlah Stok & Kategori --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                {{-- Stok --}}
                <div>
                    <label for="stock" class="block text-sm font-semibold text-slate-700 font-body mb-2">
                        Jumlah Stok <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $item->stock) }}" min="0"
                               class="input-season w-full px-4 py-3 rounded-xl text-sm font-body text-slate-800 pr-16 @error('stock') border-red-400 @enderror">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-body">unit</span>
                    </div>
                    <div class="mt-2" id="stock-preview">
                        <span id="stock-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold font-body badge-high">
                            <span id="stock-dot" class="w-1.5 h-1.5 rounded-full" style="display:inline-block; background:#22c55e"></span>
                            <span id="stock-label">Stok Aman</span>
                        </span>
                    </div>
                    @error('stock')
                        <p class="mt-1.5 text-xs text-red-600 font-body flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 font-body mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>

                    <input type="hidden" id="category" name="category" value="{{ old('category', $item->category) }}">

                    <div class="relative" id="cat-dropdown-wrapper">
                        <button type="button" id="cat-trigger"
                                onclick="toggleCatDropdown()"
                                class="input-season w-full px-4 py-3 rounded-xl text-sm font-body text-left flex items-center justify-between gap-2 @error('category') border-red-400 @enderror">
                            <span id="cat-display" class="text-slate-400">Pilih atau ketik kategori...</span>
                            <svg id="cat-chevron" class="w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="cat-panel"
                             class="absolute z-50 w-full mt-2 rounded-2xl shadow-warm-lg border border-bark-100 overflow-hidden"
                             style="display:none; background:rgba(255,255,255,0.98); backdrop-filter:blur(12px)">

                            <div class="p-3 border-b border-bark-50">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" id="cat-search"
                                           placeholder="Cari atau tambah kategori baru..."
                                           oninput="filterCategories(this.value)"
                                           class="w-full pl-9 pr-4 py-2 text-xs font-body rounded-xl border border-bark-100 bg-bark-50/50 text-slate-700 placeholder-slate-400 outline-none focus:border-bark-300">
                                </div>
                            </div>

                            <div id="cat-list" class="overflow-y-auto" style="max-height:220px">
                                @php
                                    $defaultCategories = collect(['Elektronik','Alat Tulis','Makanan','Minuman','Pakaian','Peralatan Rumah','Obat-obatan','Buku']);
                                    $allCategories = $defaultCategories->merge($categories)->unique()->sort()->values();
                                @endphp

                                @foreach($allCategories as $cat)
                                <button type="button"
                                        onclick="selectCategory('{{ $cat }}')"
                                        data-cat="{{ strtolower($cat) }}"
                                        class="cat-option w-full px-4 py-3 text-left text-sm font-body text-slate-700 hover:bg-bark-50 flex items-center gap-3 transition-colors border-b border-bark-50/50 last:border-0">
                                    <span class="cat-emoji w-7 h-7 rounded-lg flex items-center justify-center text-base flex-shrink-0" style="background:rgba(176,125,71,0.08)">{{ getCatEmoji($cat) }}</span>
                                    <span>{{ $cat }}</span>
                                </button>
                                @endforeach

                                <button type="button" id="cat-add-new"
                                        onclick="addNewCategory()"
                                        class="w-full px-4 py-3 text-left text-sm font-body text-bark-600 hover:bg-bark-50 flex items-center gap-3 transition-colors"
                                        style="display:none">
                                    <span class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(176,125,71,0.1)">
                                        <svg class="w-4 h-4 text-bark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </span>
                                    <span>Tambah "<span id="cat-new-label"></span>"</span>
                                </button>

                                <p id="cat-empty" class="px-4 py-6 text-center text-xs text-slate-400 font-body" style="display:none">Tidak ada kategori ditemukan</p>
                            </div>
                        </div>
                    </div>

                    @error('category')
                        <p class="mt-1.5 text-xs text-red-600 font-body flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-8">
                <label for="description" class="block text-sm font-semibold text-slate-700 font-body mb-2">
                    Deskripsi <span class="text-slate-400 font-normal">(opsional)</span>
                </label>
                <textarea id="description" name="description" rows="3"
                          placeholder="Catatan atau deskripsi singkat..."
                          class="input-season w-full px-4 py-3 rounded-xl text-sm font-body text-slate-800 placeholder-slate-400 resize-none @error('description') border-red-400 @enderror">{{ old('description', $item->description) }}</textarea>
                <p class="mt-1 text-xs text-slate-400 font-body" id="desc-counter">{{ strlen($item->description ?? '') }} / 500 karakter</p>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold font-body">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perbarui Barang
                </button>
                <a href="{{ route('items.index') }}"
                   class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium font-body hover:bg-slate-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Danger zone --}}
    <div class="mt-6 rounded-2xl border border-red-100 p-5 bg-red-50/50">
        <p class="text-sm font-semibold text-red-700 font-body mb-1">Zona Bahaya</p>
        <p class="text-xs text-red-400 font-body mb-3">Hapus barang ini secara permanen dari inventaris.</p>
        <button onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->name) }}')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-semibold font-body transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus Barang Ini
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ═══════════════════════════════
    // Custom Category Dropdown
    // ═══════════════════════════════
    let catOpen = false;

    function toggleCatDropdown() {
        catOpen ? closeCatDropdown() : openCatDropdown();
    }
    function openCatDropdown() {
        catOpen = true;
        document.getElementById('cat-panel').style.display = 'block';
        document.getElementById('cat-chevron').style.transform = 'rotate(180deg)';
        setTimeout(() => document.getElementById('cat-search').focus(), 50);
    }
    function closeCatDropdown() {
        catOpen = false;
        document.getElementById('cat-panel').style.display = 'none';
        document.getElementById('cat-chevron').style.transform = 'rotate(0deg)';
        document.getElementById('cat-search').value = '';
        filterCategories('');
    }
    function selectCategory(value) {
        document.getElementById('category').value = value;
        const emoji = document.querySelector(`[data-cat="${value.toLowerCase()}"] .cat-emoji`);
        document.getElementById('cat-display').innerHTML = `<span class="flex items-center gap-2 text-slate-800">
            <span>${emoji ? emoji.textContent : '📦'}</span><span>${value}</span></span>`;
        closeCatDropdown();
    }
    function filterCategories(query) {
        const q       = query.toLowerCase().trim();
        const options = document.querySelectorAll('.cat-option');
        const addNew  = document.getElementById('cat-add-new');
        const empty   = document.getElementById('cat-empty');
        let visible = 0, exact = false;
        options.forEach(opt => {
            const show = !q || opt.dataset.cat.includes(q);
            opt.style.display = show ? 'flex' : 'none';
            if (show) visible++;
            if (opt.dataset.cat === q) exact = true;
        });
        if (q && !exact) {
            document.getElementById('cat-new-label').textContent = query;
            addNew.style.display = 'flex';
        } else {
            addNew.style.display = 'none';
        }
        empty.style.display = (visible === 0 && !q) ? 'block' : 'none';
    }
    function addNewCategory() {
        const val = document.getElementById('cat-search').value.trim();
        if (!val) return;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'cat-option w-full px-4 py-3 text-left text-sm font-body text-slate-700 hover:bg-bark-50 flex items-center gap-3 transition-colors border-b border-bark-50/50';
        btn.setAttribute('data-cat', val.toLowerCase());
        btn.onclick = () => selectCategory(val);
        btn.innerHTML = `<span class="cat-emoji w-7 h-7 rounded-lg flex items-center justify-center text-base flex-shrink-0" style="background:rgba(176,125,71,0.08)">📦</span><span>${val}</span>`;
        document.getElementById('cat-list').insertBefore(btn, document.getElementById('cat-add-new'));
        selectCategory(val);
    }
    document.addEventListener('click', (e) => {
        if (catOpen && !document.getElementById('cat-dropdown-wrapper').contains(e.target)) closeCatDropdown();
    });

    // Pre-select current value
    const oldCat = document.getElementById('category').value;
    if (oldCat) selectCategory(oldCat);

    // ═══════════════════════════════
    // Stock badge
    // ═══════════════════════════════
    const stockInput = document.getElementById('stock');
    const stockBadge = document.getElementById('stock-badge');
    const stockDot   = document.getElementById('stock-dot');
    const stockLabel = document.getElementById('stock-label');

    function updateStockBadge(val) {
        stockBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold font-body ';
        if (val === 0) {
            stockBadge.className += 'badge-empty'; stockDot.style.background = '#ef4444'; stockLabel.textContent = 'Stok Habis';
        } else if (val <= 10) {
            stockBadge.className += 'badge-low';   stockDot.style.background = '#f59e0b'; stockLabel.textContent = 'Stok Menipis';
        } else if (val <= 50) {
            stockBadge.className += 'badge-medium'; stockDot.style.background = '#3b82f6'; stockLabel.textContent = 'Stok Cukup';
        } else {
            stockBadge.className += 'badge-high';  stockDot.style.background = '#22c55e'; stockLabel.textContent = 'Stok Aman';
        }
    }
    stockInput.addEventListener('input', () => updateStockBadge(parseInt(stockInput.value) || 0));
    updateStockBadge(parseInt(stockInput.value) || 0);

    // ═══════════════════════════════
    // Description counter
    // ═══════════════════════════════
    const descTextarea = document.getElementById('description');
    const descCounter  = document.getElementById('desc-counter');
    descTextarea.addEventListener('input', () => {
        const len = descTextarea.value.length;
        descCounter.textContent = len + ' / 500 karakter';
        descCounter.className = 'mt-1 text-xs font-body ' + (len > 450 ? 'text-amber-500' : 'text-slate-400');
    });

    // Set flag so page loader is skipped when redirected back to index
    document.getElementById('edit-form').addEventListener('submit', function() {
        sessionStorage.setItem('ss_skip_loader', '1');
    });
</script>
@endpush