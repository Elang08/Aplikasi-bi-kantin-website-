<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';

    public $timestamps = false;

    protected $fillable = [
        'nama_menu',
        'harga',
        'kategori',
    ];

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'menu_id');
    }
}
