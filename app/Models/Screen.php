<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['screen', 'url'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_screens');
    }
}
