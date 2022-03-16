<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SubmitExercise extends Model
{
    use Notifiable;
    protected $table = 'exercise_submited';
    protected $fillable = [
        'id', 'exerciseId', 'userId', 'url'
    ];
}
