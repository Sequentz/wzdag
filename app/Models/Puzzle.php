<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Word;
use Kyslik\ColumnSortable\Sortable;

class Puzzle extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [

        'name',
        'theme_id',
    ];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'puzzle_word'); // Zorg dat de juiste tussentabel naam wordt gebruikt
    }
}
