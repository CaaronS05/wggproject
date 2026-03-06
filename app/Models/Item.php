<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'stock',
        'category',
        'description',
    ];

    public function getStockStatusAttribute(): string
    {
        if ($this->stock === 0) {
            return 'empty';
        } elseif ($this->stock <= 10) {
            return 'low';
        } elseif ($this->stock <= 50) {
            return 'medium';
        } else {
            return 'high';
        }
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
}