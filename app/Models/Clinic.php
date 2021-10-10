<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function paramedics()
    {
        return $this->belongsToMany(Paramedic::class, 'clinic_paramedics');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
