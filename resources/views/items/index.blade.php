@extends('layouts.app')

@section('title', 'Dashboard Inventory — SeasonStock')

@section('content')

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-sm font-body uppercase tracking-widest mb-1" style="color:rgba(168,208,232,0.6)">Dashboard</p>
            <h1 class="font-display text-3xl sm:text-4xl font-semibold leading-tight" style="color:#e8f4fc">
                Inventory Barang
            </h1>
            <p class="mt-1.5 font-body text-sm" style="color:rgba(168,208,232,0.7)">Kelola stok barang Anda dengan mudah dan efisien.</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-body" style="color:rgba(168,208,232,0.5)">
            <div class="w-2 h-2 rounded-full animate-pulse" style="background:#4ade80"></div>
            <span>Live Update</span>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('items.index') }}" class="stat-card card-season rounded-2xl p-5 cursor-pointer">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(142,200,232,0.15);border:1px solid rgba(142,200,232,0.2)">
                <svg class="w-5 h-5" style="color:rgba(168,208,232,0.8)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <p class="font-display text-3xl font-semibold" style="color:#e8f4fc">{{ $stats['total'] }}</p>
        <p class="text-xs font-body mt-1" style="color:rgba(168,208,232,0.6)">Total Barang</p>
    </a>

    <a href="{{ route('items.index', ['status' => 'empty']) }}" class="stat-card card-season rounded-2xl p-5 cursor-pointer">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.2)">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </div>
        <p class="font-display text-3xl font-semibold text-red-400">{{ $stats['empty'] }}</p>
        <p class="text-xs font-body mt-1" style="color:rgba(168,208,232,0.6)">Stok Habis</p>
    </a>

    <a href="{{ route('items.index', ['status' => 'low']) }}" class="stat-card card-season rounded-2xl p-5 cursor-pointer">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.2)">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="font-display text-3xl font-semibold text-amber-400">{{ $stats['low'] }}</p>
        <p class="text-xs font-body mt-1" style="color:rgba(168,208,232,0.6)">Stok Menipis</p>
    </a>

    <a href="{{ route('items.index', ['status' => 'high']) }}" class="stat-card card-season rounded-2xl p-5 cursor-pointer">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.2)">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="font-display text-3xl font-semibold text-green-400">{{ $stats['high'] }}</p>
        <p class="text-xs font-body mt-1" style="color:rgba(168,208,232,0.6)">Stok Aman</p>
    </a>
</div>

{{-- ═══ CHART + RECENT LOGS ROW ═══ --}}
<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Grafik Stok per Kategori --}}
    <div class="card-season rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs font-body uppercase tracking-widest mb-0.5" style="color:rgba(168,208,232,0.5)">Visualisasi</p>
                <h3 class="font-display text-lg font-semibold" style="color:#e8f4fc">Stok per Kategori</h3>
            </div>
            <div class="flex gap-2">
                <button onclick="switchChart('bar')" id="btn-bar"
                        class="px-3 py-1.5 rounded-lg text-xs font-body transition-all btn-primary">Bar</button>
                <button onclick="switchChart('doughnut')" id="btn-doughnut"
                        class="px-3 py-1.5 rounded-lg text-xs font-body transition-all"
                        style="background:rgba(255,255,255,0.07);border:1px solid rgba(200,232,244,0.15);color:rgba(168,208,232,0.6)">Pie</button>
            </div>
        </div>
        @if($chartData->count() > 0)
        <div style="position:relative;height:280px"><canvas id="stockChart"></canvas></div>
        @else
        <div class="flex items-center justify-center h-52" style="color:rgba(168,208,232,0.3)">
            <p class="text-sm font-body">Belum ada data untuk ditampilkan</p>
        </div>
        @endif
    </div>

    {{-- Riwayat Terbaru --}}
    <div class="card-season rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs font-body uppercase tracking-widest mb-0.5" style="color:rgba(168,208,232,0.5)">Aktivitas</p>
                <h3 class="font-display text-lg font-semibold" style="color:#e8f4fc">Riwayat Terbaru</h3>
            </div>
            <a href="{{ route('items.history') }}" class="text-xs font-body" style="color:rgba(142,200,232,0.7)">Lihat semua →</a>
        </div>
        @if($recentLogs->count() > 0)
        <div class="space-y-3">
            @foreach($recentLogs as $log)
            @php
                $ac = match($log->action) {
                    'created' => ['bg'=>'rgba(34,197,94,0.15)', 'border'=>'rgba(34,197,94,0.3)',  'dot'=>'#4ade80', 'text'=>'#86efac'],
                    'updated' => ['bg'=>'rgba(59,130,246,0.15)','border'=>'rgba(59,130,246,0.3)', 'dot'=>'#60a5fa', 'text'=>'#93c5fd'],
                    'deleted' => ['bg'=>'rgba(239,68,68,0.15)', 'border'=>'rgba(239,68,68,0.3)',  'dot'=>'#f87171', 'text'=>'#fca5a5'],
                    default   => ['bg'=>'rgba(142,200,232,0.1)','border'=>'rgba(142,200,232,0.2)','dot'=>'#a8d0e8', 'text'=>'#c8e8f4'],
                };
            @endphp
            <div class="flex items-start gap-3 p-3 rounded-xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(200,232,244,0.08)">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                     style="background:{{ $ac['bg'] }};border:1px solid {{ $ac['border'] }}">
                    @if($log->action==='created')
                        <svg class="w-3.5 h-3.5" style="color:{{ $ac['dot'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    @elseif($log->action==='updated')
                        <svg class="w-3.5 h-3.5" style="color:{{ $ac['dot'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    @else
                        <svg class="w-3.5 h-3.5" style="color:{{ $ac['dot'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-semibold font-body truncate" style="color:#e8f4fc">{{ $log->item_name }}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold font-body"
                              style="background:{{ $ac['bg'] }};border:1px solid {{ $ac['border'] }};color:{{ $ac['text'] }}">{{ $log->action_label }}</span>
                        @if($log->stock_change !== null && $log->action !== 'created' && $log->stock_change != 0)
                            <span class="text-xs font-semibold font-body" style="color:{{ $log->stock_change > 0 ? '#4ade80' : '#f87171' }}">
                                {{ $log->stock_change > 0 ? '+' : '' }}{{ $log->stock_change }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs font-body mt-0.5 truncate" style="color:rgba(168,208,232,0.5)">{{ $log->notes }}</p>
                    <p class="text-xs font-body mt-0.5" style="color:rgba(168,208,232,0.3)">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex items-center justify-center h-52" style="color:rgba(168,208,232,0.3)">
            <p class="text-sm font-body">Belum ada aktivitas tercatat</p>
        </div>
        @endif
    </div>
</div>


{{-- Filter & Search Bar — real-time --}}
<div class="card-season rounded-2xl p-5 mb-6">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4" style="color:rgba(168,208,232,0.5)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="rt-search" value="{{ request('search') }}"
                   placeholder="Cari nama barang atau kategori..."
                   class="input-season w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-body"
                   autocomplete="off">
        </div>
        <select id="rt-category" class="input-season px-4 py-2.5 rounded-xl text-sm font-body min-w-[160px]">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select id="rt-status" class="input-season px-4 py-2.5 rounded-xl text-sm font-body min-w-[160px]">
            <option value="">Semua Status</option>
            <option value="empty"  {{ request('status') == 'empty'  ? 'selected' : '' }}>Stok Habis</option>
            <option value="low"    {{ request('status') == 'low'    ? 'selected' : '' }}>Stok Menipis</option>
            <option value="medium" {{ request('status') == 'medium' ? 'selected' : '' }}>Stok Cukup</option>
            <option value="high"   {{ request('status') == 'high'   ? 'selected' : '' }}>Stok Aman</option>
        </select>
        <button id="rt-reset" onclick="resetFilter()"
                class="px-4 py-2.5 rounded-xl text-sm font-body whitespace-nowrap transition-all"
                style="background:rgba(255,255,255,0.07);border:1px solid rgba(200,232,244,0.18);color:rgba(168,208,232,0.6);display:none">
            Reset
        </button>
    </div>
    <p id="rt-count" class="mt-2.5 text-xs font-body" style="color:rgba(168,208,232,0.5);display:none"></p>
</div>

{{-- Table --}}
<div class="card-season rounded-2xl overflow-hidden">
    @if($items->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="border-bottom:1px solid rgba(200,232,244,0.12);background:rgba(44,79,114,0.5)">
                    <th class="text-left px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">#</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Nama Barang</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Kategori</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Stok</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Kondisi</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Aksi</th>
                </tr>
            </thead>
            <tbody id="items-tbody">
                @foreach($items as $index => $item)
                @php
                    $statusColor = match($item->stock_status) {
                        'empty'  => '#f87171',
                        'low'    => '#fbbf24',
                        'medium' => '#60a5fa',
                        'high'   => '#4ade80',
                        default  => '#e8f4fc',
                    };
                @endphp
                <tr class="table-row-hover"
                    data-name="{{ strtolower($item->name) }}"
                    data-desc="{{ strtolower($item->description ?? '') }}"
                    data-category="{{ strtolower($item->category) }}"
                    data-status="{{ $item->stock_status }}"
                    style="border-bottom:1px solid rgba(200,232,244,0.07)">

                    <td class="px-6 py-4 text-sm font-body" style="color:rgba(168,208,232,0.4)">{{ $index + 1 }}</td>

                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold font-body" style="color:#e8f4fc">{{ $item->name }}</p>
                        @if($item->description)
                            <p class="text-xs font-body mt-0.5 truncate max-w-[220px]" style="color:rgba(168,208,232,0.5)">{{ $item->description }}</p>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium font-body"
                              style="background:rgba(142,200,232,0.12);border:1px solid rgba(142,200,232,0.2);color:rgba(200,232,244,0.8)">
                            {{ $item->category }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="font-display text-lg font-semibold" style="color:{{ $statusColor }}">
                            {{ number_format($item->stock) }}
                        </span>
                        <p class="text-xs font-body" style="color:rgba(168,208,232,0.4)">unit</p>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold font-body
                            @if($item->stock_status === 'empty') badge-empty
                            @elseif($item->stock_status === 'low') badge-low
                            @elseif($item->stock_status === 'medium') badge-medium
                            @else badge-high @endif">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                @if($item->stock_status === 'empty') dot-empty
                                @elseif($item->stock_status === 'low') dot-low
                                @elseif($item->stock_status === 'medium') dot-medium
                                @else dot-high @endif"
                                style="display:inline-block"></span>
                            {{ $item->stock_label }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('items.edit', $item) }}"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium font-body transition-colors"
                               style="background:rgba(142,200,232,0.1);border:1px solid rgba(142,200,232,0.18);color:rgba(168,208,232,0.8)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <button onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium font-body transition-colors"
                                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#fca5a5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
    <div class="px-6 py-4" style="border-top:1px solid rgba(200,232,244,0.1)">
        <div class="flex items-center justify-between">
            <p class="text-xs font-body" style="color:rgba(168,208,232,0.5)">
                Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} barang
            </p>
            <div class="flex gap-1">
                @if($items->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.3);border:1px solid rgba(200,232,244,0.08)">← Prev</span>
                @else
                    <a href="{{ $items->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.6);border:1px solid rgba(200,232,244,0.15)">← Prev</a>
                @endif
                @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                    @if($page == $items->currentPage())
                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium font-body btn-primary">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.6);border:1px solid rgba(200,232,244,0.15)">{{ $page }}</a>
                    @endif
                @endforeach
                @if($items->hasMorePages())
                    <a href="{{ $items->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.6);border:1px solid rgba(200,232,244,0.15)">Next →</a>
                @else
                    <span class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.3);border:1px solid rgba(200,232,244,0.08)">Next →</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    @else
    <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-5"
             style="background:rgba(142,200,232,0.1);border:1px solid rgba(142,200,232,0.15)">
            <svg class="w-10 h-10" style="color:rgba(142,200,232,0.4)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <h3 class="font-display text-xl mb-2" style="color:rgba(200,232,244,0.8)">Belum Ada Barang</h3>
        @if(request()->hasAny(['search','category','status']))
            <p class="text-sm font-body mb-5" style="color:rgba(168,208,232,0.5)">Tidak ada barang yang cocok dengan filter.</p>
            <a href="{{ route('items.index') }}" class="text-sm font-medium font-body" style="color:rgba(142,200,232,0.8)">Hapus filter</a>
        @else
            <p class="text-sm font-body mb-5" style="color:rgba(168,208,232,0.5)">Mulai dengan menambahkan barang pertama Anda.</p>
            <a href="{{ route('items.create') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium font-body">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Barang Pertama
            </a>
        @endif
    </div>
    @endif
</div>

{{-- Stock Legend --}}
<div class="mt-6 card-season rounded-2xl p-5">
    <p class="text-xs font-semibold uppercase tracking-wider font-body mb-3" style="color:rgba(168,208,232,0.5)">Keterangan Status Stok</p>
    <div class="flex flex-wrap gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold badge-high font-body">
                <span class="w-1.5 h-1.5 rounded-full" style="display:inline-block;background:#22c55e"></span>Stok Aman
            </span>
            <span class="text-xs font-body" style="color:rgba(168,208,232,0.4)">&gt; 50 unit</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold badge-medium font-body">
                <span class="w-1.5 h-1.5 rounded-full" style="display:inline-block;background:#3b82f6"></span>Stok Cukup
            </span>
            <span class="text-xs font-body" style="color:rgba(168,208,232,0.4)">11 – 50 unit</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold badge-low font-body">
                <span class="w-1.5 h-1.5 rounded-full" style="display:inline-block;background:#f59e0b"></span>Stok Menipis
            </span>
            <span class="text-xs font-body" style="color:rgba(168,208,232,0.4)">1 – 10 unit</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold badge-empty font-body">
                <span class="w-1.5 h-1.5 rounded-full" style="display:inline-block;background:#ef4444"></span>Stok Habis
            </span>
            <span class="text-xs font-body" style="color:rgba(168,208,232,0.4)">0 unit</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
@if($chartData->count() > 0)
const chartLabels = @json($chartData->pluck('category'));
const chartValues = @json($chartData->pluck('total_stock'));
const chartCounts = @json($chartData->pluck('item_count'));

const COLORS = ['rgba(96,165,250,0.85)','rgba(74,222,128,0.85)','rgba(251,191,36,0.85)','rgba(248,113,113,0.85)','rgba(167,139,250,0.85)','rgba(45,212,191,0.85)','rgba(251,146,60,0.85)','rgba(244,114,182,0.85)'];
const BORDERS = COLORS.map(c=>c.replace('0.85','1'));

Chart.defaults.color = 'rgba(168,208,232,0.7)';
Chart.defaults.borderColor = 'rgba(200,232,244,0.08)';
let currentChart = null;

function buildConfig(type) {
    if (type === 'bar') return {
        type: 'bar',
        data: { labels: chartLabels, datasets: [{ label:'Total Stok', data: chartValues, backgroundColor: COLORS, borderColor: BORDERS, borderWidth:1, borderRadius:8, borderSkipped:false }] },
        options: { responsive:true, maintainAspectRatio:false,
            plugins: { legend:{display:false}, tooltip:{ backgroundColor:'rgba(15,32,62,0.95)', borderColor:'rgba(200,232,244,0.2)', borderWidth:1, titleColor:'#e8f4fc', bodyColor:'rgba(168,208,232,0.8)', padding:12, callbacks:{ label: ctx => ` ${ctx.parsed.y} unit (${chartCounts[ctx.dataIndex]} barang)` }}},
            scales: { x:{ grid:{color:'rgba(200,232,244,0.06)'}, ticks:{font:{family:'DM Sans',size:11},maxRotation:30} }, y:{ grid:{color:'rgba(200,232,244,0.06)'}, ticks:{font:{family:'DM Sans',size:11}}, beginAtZero:true } }
        }
    };
    return {
        type: 'doughnut',
        data: { labels: chartLabels, datasets: [{ data: chartValues, backgroundColor: COLORS, borderColor:'rgba(15,32,62,0.8)', borderWidth:3, hoverOffset:8 }] },
        options: { responsive:true, maintainAspectRatio:false, cutout:'62%',
            plugins: { legend:{ position:'right', labels:{ font:{family:'DM Sans',size:11}, color:'rgba(168,208,232,0.8)', padding:12, usePointStyle:true, pointStyleWidth:8 }}, tooltip:{ backgroundColor:'rgba(15,32,62,0.95)', borderColor:'rgba(200,232,244,0.2)', borderWidth:1, titleColor:'#e8f4fc', bodyColor:'rgba(168,208,232,0.8)', padding:12, callbacks:{ label: ctx => ` ${ctx.parsed} unit (${chartCounts[ctx.dataIndex]} barang)` }}}
        }
    };
}

function switchChart(type) {
    if (currentChart) currentChart.destroy();
    currentChart = new Chart(document.getElementById('stockChart').getContext('2d'), buildConfig(type));
    const inactiveStyle = 'background:rgba(255,255,255,0.07);border:1px solid rgba(200,232,244,0.15);color:rgba(168,208,232,0.6)';
    const btnBar = document.getElementById('btn-bar');
    const btnPie = document.getElementById('btn-doughnut');
    if (type === 'bar') {
        btnBar.classList.add('btn-primary'); btnBar.removeAttribute('style');
        btnPie.classList.remove('btn-primary'); btnPie.setAttribute('style', inactiveStyle);
    } else {
        btnPie.classList.add('btn-primary'); btnPie.removeAttribute('style');
        btnBar.classList.remove('btn-primary'); btnBar.setAttribute('style', inactiveStyle);
    }
}
document.addEventListener('DOMContentLoaded', () => switchChart('bar'));
@endif
</script>

<script>
(function () {
    const searchEl   = document.getElementById('rt-search');
    const categoryEl = document.getElementById('rt-category');
    const statusEl   = document.getElementById('rt-status');
    const countEl    = document.getElementById('rt-count');
    const tbody      = document.getElementById('items-tbody');
    if (!searchEl || !tbody) return;

    const allRows = () => Array.from(tbody.querySelectorAll('tr[data-name]'));

    function applyFilter() {
        const q   = searchEl.value.trim().toLowerCase();
        const cat = categoryEl.value.toLowerCase();
        const st  = statusEl.value.toLowerCase();
        const hasFilter = q || cat || st;

        document.getElementById('rt-reset').style.display = hasFilter ? 'block' : 'none';

        let visible = 0;
        allRows().forEach(row => {
            const show = (!q  || row.dataset.name.includes(q) || (row.dataset.desc||'').includes(q) || row.dataset.category.includes(q))
                      && (!cat || row.dataset.category === cat)
                      && (!st  || row.dataset.status   === st);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (hasFilter) {
            countEl.style.display = 'block';
            countEl.textContent   = `Menampilkan ${visible} dari ${allRows().length} barang`;
        } else {
            countEl.style.display = 'none';
        }

        let emptyRow = tbody.querySelector('.rt-empty-row');
        if (visible === 0 && allRows().length > 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'rt-empty-row';
                emptyRow.innerHTML = `<td colspan="6" class="px-6 py-12 text-center text-sm font-body" style="color:rgba(168,208,232,0.4)">Tidak ada barang yang cocok dengan filter.</td>`;
                tbody.appendChild(emptyRow);
            }
        } else if (emptyRow) {
            emptyRow.remove();
        }
    }

    let debounce;
    searchEl.addEventListener('input',    () => { clearTimeout(debounce); debounce = setTimeout(applyFilter, 160); });
    categoryEl.addEventListener('change', applyFilter);
    statusEl.addEventListener('change',   applyFilter);
    applyFilter();
})();

function resetFilter() {
    document.getElementById('rt-search').value   = '';
    document.getElementById('rt-category').value = '';
    document.getElementById('rt-status').value   = '';
    document.getElementById('rt-reset').style.display = 'none';
    document.getElementById('rt-count').style.display = 'none';
    document.getElementById('items-tbody')?.querySelectorAll('tr').forEach(r => r.style.display = '');
    document.querySelector('.rt-empty-row')?.remove();
}
</script>
@endpush