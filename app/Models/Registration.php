<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['patient_id', 'paramedic_id', 'user_id_created', 'user_id_updated', 'queue_number',  'queue_status', 'registration_number', 'registration_status', 'registration_date', 'registration_hour', 'clinic_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function paramedic()
    {
        return $this->belongsTo(Paramedic::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_id_created', 'id');
    }

    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'user_id_updated', 'id');
    }
}
