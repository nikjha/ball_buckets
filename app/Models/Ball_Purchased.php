<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ball_Purchased extends Model
{
    use HasFactory;
    protected $fillable = ['ball_id', 'qty'];
    public function ball()
    {
        return $this->belongsTo(Ball::class, 'ball_id', 'id');
    }
}
