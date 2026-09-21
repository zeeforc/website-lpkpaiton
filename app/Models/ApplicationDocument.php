<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    protected $fillable = ['application_id', 'file_path', 'original_name', 'status', 'keterangan'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
