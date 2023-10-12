<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'lesson_id',
        'lesson_order',
        'level_id', 
        'axis_id', 
        'lesson_name'
    ];

}
