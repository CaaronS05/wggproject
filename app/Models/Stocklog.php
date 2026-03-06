<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = [
        'item_id', 'item_name', 'action',
        'stock_before', 'stock_after', 'stock_change',
        'category', 'notes',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'created' => 'Ditambahkan',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            default   => $this->action,
        };
    }

    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            default   => 'gray',
        };
    }
}