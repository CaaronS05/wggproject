<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'stock', 'category', 'description'];

    // ── Accessors ────────────────────────────────────
    public function getStockStatusAttribute(): string
    {
        if ($this->stock === 0)       return 'empty';
        elseif ($this->stock <= 10)   return 'low';
        elseif ($this->stock <= 50)   return 'medium';
        else                          return 'high';
    }

    public function getStockLabelAttribute(): string
    {
        return match($this->stock_status) {
            'empty'  => 'Stok Habis',
            'low'    => 'Stok Menipis',
            'medium' => 'Stok Cukup',
            'high'   => 'Stok Aman',
            default  => 'Unknown',
        };
    }


    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }
}