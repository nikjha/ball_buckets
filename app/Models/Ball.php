<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ball extends Model
{
    protected $fillable = ['qty', 'color', 'volume'];

    // public function bucket()
    // {
    //     return $this->belongsTo(Bucket::class);
    // }
}
