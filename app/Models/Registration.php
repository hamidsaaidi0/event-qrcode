<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'qr_code_data', 'used'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
