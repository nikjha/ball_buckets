<?php 
// BallController.php
namespace App\Http\Controllers;

use App\Models\Ball;
use Illuminate\Http\Request;

class BallController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'color' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
        ]);

        // Create a new ball instance
        $ball = new Ball();
        $ball->color = $request->input('color');
        $ball->volume = $request->input('volume');
        $ball->qty = $request->input('qty');        

        // Save the ball to the database
        $ball->save();
        return response()->json(['message' => 'Ball created successfully'], 201);
    }
}

