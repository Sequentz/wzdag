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

    public function woorden()
    {
        return $this->belongsToMany(Word::class, 'puzzle_woord');
    }
}
