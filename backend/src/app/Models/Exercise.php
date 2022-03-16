<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Exercise extends Model
{
    use Notifiable;
    protected $table = 'exercise';
    protected $fillable = [
        'id', 'authorId', 'title', 'description', 'url'
    ];
}
