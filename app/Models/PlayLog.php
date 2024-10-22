<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayLog extends Model
{
    use HasFactory;

    // Geef de velden aan die je wilt invullen met mass assignment
    protected $fillable = ['ip_address', 'last_played'];
}
