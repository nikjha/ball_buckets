<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    protected $fillable = ['name', 'volume'];

    public function balls()
    {
        return $this->hasMany(Ball::class);
    }

    public function placeBalls($balls)
    {
        $remainingVolume = $this->volume;
        $placedBalls = [];

        foreach ($balls as $color => $volume) {
            $count = floor($remainingVolume / $volume);
            $count = min($count, $balls[$color]); 

            if ($count > 0) {
                $this->balls()->createMany(array_fill(0, $count, ['name' => $color . ' Ball', 'color' => $color, 'volume' => $volume]));
                $remainingVolume -= $count * $volume;
                $placedBalls[$color] = $count;
            }
        }

        return $placedBalls;
    }
}

