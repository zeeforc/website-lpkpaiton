<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'company',
        'photo',
        'quote',
        'is_active',
    ];
}
