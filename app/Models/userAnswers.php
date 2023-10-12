<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userAnswers extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'userAnswer_id',
        'question_id',
        'lesson_id',
        'level_id', 
        'axis_id', 
        'user_id',
        'userAnswer_question',
        'userAnswer_answer',
        'userAnswer_isTrue',
        'userAnswer_date'
    ];

}
