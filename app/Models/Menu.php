<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['menu'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'menu_users');
    }

    public function screens()
    {
        return $this->belongsToMany(Screen::class, 'menu_screens')->withTimestamps()->withPivot('number_order');
    }
}
