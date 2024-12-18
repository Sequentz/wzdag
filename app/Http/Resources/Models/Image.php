<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Image extends Model
{
    use HasFactory;
    use Sortable;
    public $sortable = ['id', 'file_name', 'created_at'];
    protected $fillable = ['file_name', 'file_path', 'file_extension', 'user_id'];
    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'theme_image');
    }

    public function puzzles()
    {
        return $this->hasMany(Puzzle::class, 'image_id');
    }
}
