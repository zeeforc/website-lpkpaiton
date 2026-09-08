<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'date', 'type', 'reason', 'attachment_path', 'status', 'admin_notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
