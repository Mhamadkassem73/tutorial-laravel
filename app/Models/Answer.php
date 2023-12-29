<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'answer_id',
        'question_id',
        'question_value',
        'question_isTrue',
        'question_right',
        'question_left', 
        'question_count',
        "question_voice",
        "question_image"
    ];
 
}
