<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paramedic extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'paramedic_type', 'registration_number', 'phone_number', 'address', 'identity_type', 'identity_number'];

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_paramedics')->withPivot('id');
    }
}
