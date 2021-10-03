<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuScreen extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'screen_id'];

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }
}
