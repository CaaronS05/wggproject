@extends('layouts.app')

@section('title', 'Tambah Barang — SeasonStock')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm font-body mb-6">
    <a href="{{ route('items.index') }}" class="text-bark-500 hover:text-bark-700 transition-colors">Inventory</a>
    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-500">Tambah Barang</span>
</nav>

<div class="max-w-2xl">
    <div class="mb-8">
        <p class="text-sm text-bark-500 font-body uppercase tracking-widest mb-1">Formulir</p>
        <h1 class="font-display text-3xl text-bark-900 font-semibold">Tambah Barang Baru</h1>
        <p class="mt-1.5 text-slate-500 font-body text-sm">Isi detail barang yang ingin ditambahkan ke inventaris.</p>
    </div>

    <div class="card-season rounded-2xl p-8">
        <form method="POST" action="{{ route('items.store') }}" id="item-form">
            @csrf

            {{-- Nama Barang --}}
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-slate-700 font-body mb-2">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       placeholder="Contoh: Buku Tulis A5"
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
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0"
                               class="input-season w-full px-4 py-3 rounded-xl text-sm font-body text-slate-800 pr-16 @error('stock') border-red-400 @enderror">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-body">unit</span>
                    </div>
                    <div class="mt-2">
                        <span id="stock-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold font-body badge-empty">
                            <span id="stock-dot" class="w-1.5 h-1.5 rounded-full" style="display:inline-block"></span>
                            <span id="stock-label">Stok Habis</span>
                        </span>
                    </div>
                    @error('stock')
                        <p class="mt-1.5 text-xs text-red-600 font-body flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kategori Custom Dropdown --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 font-body mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>

                    <input type="hidden" id="category" name="category" value="{{ old('category') }}">

                    <div style="position:relative" id="cat-wrapper">
                        <button type="button" id="cat-btn"
                                onclick="catToggle()"
                                class="@error('category') border-red-400 @enderror"
                                style="width:100%;padding:12px 16px;border:1.5px solid #e4d3b3;border-radius:12px;
                                       background:white;cursor:pointer;display:flex;align-items:center;
                                       justify-content:space-between;gap:8px;transition:all 0.2s;
                                       font-family:'DM Sans',sans-serif">
                            <span id="cat-display" style="font-size:13.5px;color:#9aaab8;flex:1;text-align:left">
                                Pilih kategori...
                            </span>
                            <svg id="cat-arrow" style="width:16px;height:16px;color:#9aaab8;flex-shrink:0;transition:transform 0.2s"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="cat-panel"
                             style="display:none;position:absolute;top:calc(100% + 8px);left:0;right:0;z-index:9999;
                                    background:white;border:1.5px solid #e4d3b3;border-radius:16px;
                                    box-shadow:0 16px 48px rgba(52,40,28,0.16);overflow:hidden">

                            <div style="padding:10px;border-bottom:1px solid #f2ead9">
                                <div style="position:relative">
                                    <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px"
                                         fill="none" stroke="#9aaab8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" id="cat-search" autocomplete="off"
                                           placeholder="Cari atau ketik kategori baru..."
                                           oninput="catFilter(this.value)"
                                           style="width:100%;padding:8px 12px 8px 32px;font-size:12.5px;
                                                  border:1.5px solid #e4d3b3;border-radius:10px;outline:none;
                                                  font-family:'DM Sans',sans-serif;color:#3c505e;background:#faf7f2;
                                                  box-sizing:border-box"
                                           onfocus="this.style.borderColor='#b07d47';this.style.boxShadow='0 0 0 3px rgba(176,125,71,0.1)'"
                                           onblur="this.style.borderColor='#e4d3b3';this.style.boxShadow='none'">
                                </div>
                            </div>

                            <div id="cat-list" style="max-height:220px;overflow-y:auto">
                                @php
                                    $defaultCats = ['Alat Tulis','Buku','Elektronik','Makanan','Minuman','Obat-obatan','Pakaian','Peralatan Rumah'];
                                    $dbCats = $categories->toArray();
                                    $merged = array_values(array_unique(array_merge($defaultCats, $dbCats)));
                                    sort($merged);
                                    $emojiMap = [
                                        'alat tulis'      => ['emoji'=>'✏️', 'bg'=>'#fef9c3'],
                                        'buku'            => ['emoji'=>'📚', 'bg'=>'#fef3c7'],
                                        'elektronik'      => ['emoji'=>'💻', 'bg'=>'#dbeafe'],
                                        'makanan'         => ['emoji'=>'🍱', 'bg'=>'#dcfce7'],
                                        'minuman'         => ['emoji'=>'🥤', 'bg'=>'#cffafe'],
                                        'obat-obatan'     => ['emoji'=>'💊', 'bg'=>'#fce7f3'],
                                        'pakaian'         => ['emoji'=>'👕', 'bg'=>'#ede9fe'],
                                        'peralatan rumah' => ['emoji'=>'🏠', 'bg'=>'#ffedd5'],
                                    ];
                                @endphp

                                @foreach($merged as $i => $cat)
                                @php
                                    $info = $emojiMap[strtolower($cat)] ?? ['emoji'=>'📦','bg'=>'#f2ead9'];
                                @endphp
                                <div class="cat-item"
                                     data-value="{{ $cat }}"
                                     data-search="{{ strtolower($cat) }}"
                                     onclick="catSelect('{{ $cat }}', '{{ $info['emoji'] }}', '{{ $info['bg'] }}')"
                                     style="display:flex;align-items:center;gap:12px;padding:11px 14px;
                                            cursor:pointer;border-bottom:1px solid #faf7f2;
                                            font-family:'DM Sans',sans-serif;font-size:13.5px;color:#496070"
                                     onmouseover="this.style.background='#faf7f2'"
                                     onmouseout="this.style.background='white'">
                                    <span style="width:34px;height:34px;border-radius:9px;background:{{ $info['bg'] }};
                                                 display:flex;align-items:center;justify-content:center;
                                                 font-size:17px;flex-shrink:0">{{ $info['emoji'] }}</span>
                                    <span style="flex:1">{{ $cat }}</span>
                                    <span class="cat-check" data-for="{{ $cat }}"
                                          style="display:none;color:#b07d47;flex-shrink:0">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </div>
                                @endforeach

                                <div id="cat-add-btn"
                                     onclick="catAddNew()"
                                     style="display:none;align-items:center;gap:12px;padding:11px 14px;
                                            cursor:pointer;font-family:'DM Sans',sans-serif;
                                            font-size:13.5px;color:#b07d47;font-weight:500"
                                     onmouseover="this.style.background='#faf7f2'"
                                     onmouseout="this.style.background='white'">
                                    <span style="width:34px;height:34px;border-radius:9px;background:#f2ead9;
                                                 display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                        <svg width="16" height="16" fill="none" stroke="#b07d47" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </span>
                                    Tambah "<strong id="cat-add-text"></strong>"
                                </div>

                                <p id="cat-empty"
                                   style="display:none;padding:24px;text-align:center;font-size:12px;
                                          color:#9aaab8;font-family:'DM Sans',sans-serif">
                                    Ketik untuk menambah kategori baru
                                </p>
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
                          placeholder="Tambahkan catatan atau deskripsi singkat..."
                          class="input-season w-full px-4 py-3 rounded-xl text-sm font-body text-slate-800 placeholder-slate-400 resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                <p class="mt-1 text-xs text-slate-400 font-body" id="desc-counter">0 / 500 karakter</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold font-body">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Barang
                </button>
                <a href="{{ route('items.index') }}"
                   class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium font-body hover:bg-slate-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let catIsOpen = false;

    function catToggle() { catIsOpen ? catClose() : catOpen(); }

    function catOpen() {
        catIsOpen = true;
        document.getElementById('cat-panel').style.display = 'block';
        document.getElementById('cat-arrow').style.transform = 'rotate(180deg)';
        document.getElementById('cat-btn').style.borderColor = '#b07d47';
        document.getElementById('cat-btn').style.boxShadow = '0 0 0 3px rgba(176,125,71,0.12)';
        setTimeout(() => document.getElementById('cat-search').focus(), 60);
    }

    function catClose() {
        catIsOpen = false;
        document.getElementById('cat-panel').style.display = 'none';
        document.getElementById('cat-arrow').style.transform = 'rotate(0deg)';
        document.getElementById('cat-btn').style.borderColor = '#e4d3b3';
        document.getElementById('cat-btn').style.boxShadow = 'none';
        document.getElementById('cat-search').value = '';
        catFilter('');
    }

    function catSelect(value, emoji, bg) {
        document.getElementById('category').value = value;

        // Reset semua checkmark
        document.querySelectorAll('.cat-check').forEach(el => el.style.display = 'none');
        // Tampilkan checkmark yang dipilih
        const check = document.querySelector(`.cat-check[data-for="${value}"]`);
        if (check) check.style.display = 'block';

        document.getElementById('cat-display').innerHTML =
            `<span style="display:flex;align-items:center;gap:8px;color:#496070">
                <span style="width:22px;height:22px;border-radius:6px;background:${bg};
                             display:flex;align-items:center;justify-content:center;font-size:13px">${emoji}</span>
                <span>${value}</span>
            </span>`;

        catClose();
    }

    function catFilter(q) {
        q = q.toLowerCase().trim();
        const items   = document.querySelectorAll('.cat-item');
        const addBtn  = document.getElementById('cat-add-btn');
        const emptyEl = document.getElementById('cat-empty');
        let count = 0, exact = false;

        items.forEach(item => {
            const match = !q || item.dataset.search.includes(q);
            item.style.display = match ? 'flex' : 'none';
            if (match) count++;
            if (item.dataset.search === q) exact = true;
        });

        if (q && !exact) {
            document.getElementById('cat-add-text').textContent = document.getElementById('cat-search').value.trim();
            addBtn.style.display = 'flex';
        } else {
            addBtn.style.display = 'none';
        }

        emptyEl.style.display = (count === 0 && !q) ? 'block' : 'none';
    }

    function catAddNew() {
        const val = document.getElementById('cat-search').value.trim();
        if (!val) return;
        const list = document.getElementById('cat-list');
        const div  = document.createElement('div');
        div.className = 'cat-item';
        div.dataset.value  = val;
        div.dataset.search = val.toLowerCase();
        div.onclick = () => catSelect(val, '📦', '#f2ead9');
        div.style.cssText = 'display:flex;align-items:center;gap:12px;padding:11px 14px;cursor:pointer;border-bottom:1px solid #faf7f2;font-family:"DM Sans",sans-serif;font-size:13.5px;color:#496070';
        div.onmouseover = () => div.style.background = '#faf7f2';
        div.onmouseout  = () => div.style.background = 'white';
        div.innerHTML = `
            <span style="width:34px;height:34px;border-radius:9px;background:#f2ead9;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0">📦</span>
            <span style="flex:1">${val}</span>
            <span class="cat-check" data-for="${val}" style="display:none;color:#b07d47">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </span>`;
        list.insertBefore(div, document.getElementById('cat-add-btn'));
        catSelect(val, '📦', '#f2ead9');
    }

    document.addEventListener('click', e => {
        if (catIsOpen && !document.getElementById('cat-wrapper').contains(e.target)) catClose();
    });

    // Pre-select old value
    const oldVal = document.getElementById('category').value;
    if (oldVal) {
        const match = document.querySelector(`.cat-item[data-value="${oldVal}"]`);
        if (match) match.click();
    }

    // Stock badge
    const stockInput = document.getElementById('stock');
    function updateStockBadge(val) {
        const badge = document.getElementById('stock-badge');
        const dot   = document.getElementById('stock-dot');
        const lbl   = document.getElementById('stock-label');
        badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold font-body ';
        if (val === 0)       { badge.className += 'badge-empty';  dot.style.background = '#ef4444'; lbl.textContent = 'Stok Habis'; }
        else if (val <= 10)  { badge.className += 'badge-low';    dot.style.background = '#f59e0b'; lbl.textContent = 'Stok Menipis'; }
        else if (val <= 50)  { badge.className += 'badge-medium'; dot.style.background = '#3b82f6'; lbl.textContent = 'Stok Cukup'; }
        else                 { badge.className += 'badge-high';   dot.style.background = '#22c55e'; lbl.textContent = 'Stok Aman'; }
    }
    stockInput.addEventListener('input', () => updateStockBadge(parseInt(stockInput.value) || 0));
    updateStockBadge(parseInt(stockInput.value) || 0);

    // Desc counter
    const desc = document.getElementById('description');
    const ctr  = document.getElementById('desc-counter');
    desc.addEventListener('input', () => {
        ctr.textContent = desc.value.length + ' / 500 karakter';
        ctr.className = 'mt-1 text-xs font-body ' + (desc.value.length > 450 ? 'text-amber-500' : 'text-slate-400');
    });

    // Set flag so page loader is skipped when redirected back to index
    document.getElementById('item-form').addEventListener('submit', function() {
        sessionStorage.setItem('ss_skip_loader', '1');
    });
</script>
@endpush