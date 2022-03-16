<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Avatar extends Model
{
    use Notifiable;
    protected $table = 'avatar';
    protected $fillable = [
        'id', 'url'
    ];
}
