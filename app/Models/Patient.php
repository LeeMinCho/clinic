<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['medical_number', 'full_name', 'place_of_birth', 'date_of_birth', 'address', 'phone_number', 'email', 'identity_type', 'identity_number', 'user_id_created', 'user_id_updated', 'user_id_deleted'];
}
