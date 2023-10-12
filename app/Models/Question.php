<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'question_id',
        'lesson_id',
        'level_id', 
        'axis_id', 
        'question_name',
        'question_type',
        'question_order',
        "question_image",
        "question_voice"
    ];

}
