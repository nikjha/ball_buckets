<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ball_Purchased;
class Ball extends Model
{
    
    protected $fillable = ['qty', 'color', 'volume'];


    public function purchases()
    {
        return $this->hasMany(Ball_Purchased::class, 'ball_id', 'id');
    }
}
