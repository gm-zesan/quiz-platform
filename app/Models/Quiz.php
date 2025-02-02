<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'is_public', 'start_time', 'end_time', 'timer', 'total_question'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
