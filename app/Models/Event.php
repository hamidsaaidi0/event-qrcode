<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'date', 'location'];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
