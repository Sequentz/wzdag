<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Theme extends Model
{
    use HasFactory;
    use Sortable;

    public $sortable = ['id', 'name', 'created_at', 'updated_at'];

    protected $fillable = ['name'];


    public function puzzles()
    {
        return $this->hasMany(Puzzle::class);
    }
    public function images()
    {
        return $this->belongsToMany(Image::class, 'theme_image');
    }
}
