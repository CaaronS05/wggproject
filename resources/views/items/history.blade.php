@extends('layouts.app')

@section('title', 'Riwayat Stok — SeasonStock')

@section('content')

{{-- Header --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('items.index') }}" class="text-sm font-body transition-colors" style="color:rgba(142,200,232,0.6)">
            ← Dashboard
        </a>
    </div>
    <p class="text-sm font-body uppercase tracking-widest mb-1" style="color:rgba(168,208,232,0.6)">Log Aktivitas</p>
    <h1 class="font-display text-3xl sm:text-4xl font-semibold" style="color:#e8f4fc">Riwayat Perubahan Stok</h1>
    <p class="mt-1.5 font-body text-sm" style="color:rgba(168,208,232,0.7)">Semua aktivitas penambahan, pengeditan, dan penghapusan barang.</p>
</div>

{{-- Filter Bar --}}
<div class="card-season rounded-2xl p-5 mb-6">
    <form method="GET" action="{{ route('items.history') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4" style="color:rgba(168,208,232,0.5)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama barang..."
                   class="input-season w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-body">
        </div>
        <select name="action" class="input-season px-4 py-2.5 rounded-xl text-sm font-body min-w-[160px]">
            <option value="">Semua Aksi</option>
            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Ditambahkan</option>
            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Diperbarui</option>
            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Dihapus</option>
        </select>
        <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-medium font-body whitespace-nowrap">
            Filter
        </button>
        @if(request()->hasAny(['search','action']))
            <a href="{{ route('items.history') }}" class="px-4 py-2.5 rounded-xl text-sm font-body whitespace-nowrap"
               style="background:rgba(255,255,255,0.07);border:1px solid rgba(200,232,244,0.18);color:rgba(168,208,232,0.6)">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Logs Table --}}
<div class="card-season rounded-2xl overflow-hidden">
    @if($logs->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="border-bottom:1px solid rgba(200,232,244,0.12);background:rgba(44,79,114,0.5)">
                    <th class="text-left px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Waktu</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Barang</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Aksi</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Sebelum</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Sesudah</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Perubahan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold uppercase tracking-wider font-body" style="color:rgba(168,208,232,0.6)">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                @php
                    $ac = match($log->action) {
                        'created' => ['bg'=>'rgba(34,197,94,0.15)', 'border'=>'rgba(34,197,94,0.3)',  'text'=>'#86efac'],
                        'updated' => ['bg'=>'rgba(59,130,246,0.15)','border'=>'rgba(59,130,246,0.3)', 'text'=>'#93c5fd'],
                        'deleted' => ['bg'=>'rgba(239,68,68,0.15)', 'border'=>'rgba(239,68,68,0.3)',  'text'=>'#fca5a5'],
                        default   => ['bg'=>'rgba(142,200,232,0.1)','border'=>'rgba(142,200,232,0.2)','text'=>'#c8e8f4'],
                    };
                @endphp
                <tr class="table-row-hover" style="border-bottom:1px solid rgba(200,232,244,0.07)">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-body" style="color:rgba(200,232,244,0.8)">{{ $log->created_at->format('d M Y') }}</p>
                        <p class="text-xs font-body mt-0.5" style="color:rgba(168,208,232,0.4)">{{ $log->created_at->format('H:i:s') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold font-body" style="color:#e8f4fc">{{ $log->item_name }}</p>
                        @if($log->category)
                            <p class="text-xs font-body mt-0.5" style="color:rgba(168,208,232,0.4)">{{ $log->category }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold font-body"
                              style="background:{{ $ac['bg'] }};border:1px solid {{ $ac['border'] }};color:{{ $ac['text'] }}">
                            {{ $log->action_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-body text-sm" style="color:rgba(168,208,232,0.6)">
                            {{ $log->stock_before !== null ? number_format($log->stock_before) . ' unit' : '—' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-body text-sm" style="color:rgba(168,208,232,0.6)">
                            {{ $log->stock_after !== null ? number_format($log->stock_after) . ' unit' : '—' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($log->stock_change !== null)
                            @php $delta = $log->stock_change; @endphp
                            <span class="font-semibold font-body text-sm"
                                  style="color:{{ $delta > 0 ? '#4ade80' : ($delta < 0 ? '#f87171' : 'rgba(168,208,232,0.5)') }}">
                                {{ $delta > 0 ? '+' : '' }}{{ number_format($delta) }}
                            </span>
                        @else
                            <span style="color:rgba(168,208,232,0.3)">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <p class="text-xs font-body" style="color:rgba(168,208,232,0.5)">{{ $log->notes ?? '—' }}</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="px-6 py-4" style="border-top:1px solid rgba(200,232,244,0.1)">
        <div class="flex items-center justify-between">
            <p class="text-xs font-body" style="color:rgba(168,208,232,0.5)">
                Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log
            </p>
            <div class="flex gap-1">
                @if($logs->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.3);border:1px solid rgba(200,232,244,0.08)">← Prev</span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.6);border:1px solid rgba(200,232,244,0.15)">← Prev</a>
                @endif
                @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    @if($page == $logs->currentPage())
                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium font-body btn-primary">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.6);border:1px solid rgba(200,232,244,0.15)">{{ $page }}</a>
                    @endif
                @endforeach
                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-body" style="color:rgba(168,208,232,0.6);border:1px solid rgba(200,232,244,0.15)">Next →</a>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="font-display text-xl mb-2" style="color:rgba(200,232,244,0.8)">Belum Ada Riwayat</h3>
        <p class="text-sm font-body" style="color:rgba(168,208,232,0.5)">Aktivitas akan tercatat di sini setelah ada perubahan barang.</p>
    </div>
    @endif
</div>

@endsection