<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'menu_id',
        'jumlah',
        'total_harga',
        'status',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
