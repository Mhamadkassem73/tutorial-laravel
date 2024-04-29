<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "zooms";
    protected $fillable = [
        'zoom_id',
        'zoom_date',
        'user_id' ,
        'zoom_url',
        'zoom_password',
        'zoom_meetingid'
    ];
}

