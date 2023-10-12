<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Axis extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'axis_id',
        'level_id',
        'axis_order',
        'axis_name',

    ];

}
