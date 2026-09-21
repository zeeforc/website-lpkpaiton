<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'date', 'end_date', 'type', 'reason', 'attachment_path', 'status', 'admin_notes'];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
