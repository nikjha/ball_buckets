<?php
namespace App\Http\Controllers;
use App\Models\Bucket;
use App\Models\Ball;
use Illuminate\Http\Request;

class BucketController extends Controller
{
    public function index()
    {
        $buckets = Bucket::all();
        $balls = Ball::all();
        
        $totalVolume = $buckets[0]->volume; 
        $suggestions = $this->calculateBallsForSpace($totalVolume);
        return view('buckets.index', compact('buckets','balls', 'suggestions'));
    }

    public function create()
    {
        return view('buckets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0.1',
        ]);

        $bucket = Bucket::create([
            'name' => $request->input('name'),
            'volume' => $request->input('volume'),
        ]);
        

        return redirect()->route('buckets.index')->with('success', 'Bucket created successfully.');
    }

    public function edit(Bucket $bucket)
    {
        return view('buckets.edit', compact('bucket'));
    }

    public function update(Request $request, Bucket $bucket)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0.1',
        ]);

        $bucket->update([
            'name' => $request->input('name'),
            'volume' => $request->input('volume'),
        ]);

        return redirect()->route('buckets.index')->with('success', 'Bucket updated successfully.');
    }

    public function destroy(Bucket $bucket)
    {
        $bucket->balls()->delete(); 
        $bucket->delete();

        return redirect()->route('buckets.index')->with('success', 'Bucket deleted successfully.');
    }

    public function placeBalls(Request $request, Bucket $bucket)
    {
        $request->validate([
            'pink' => 'required|integer|min:0',
            'red' => 'required|integer|min:0',
            'blue' => 'required|integer|min:0',
            'orange' => 'required|integer|min:0',
            'green' => 'required|integer|min:0',
        ]);

        $balls = [
            'pink' => $request->input('pink'),
            'red' => $request->input('red'),
            'blue' => $request->input('blue'),
            'orange' => $request->input('orange'),
            'green' => $request->input('green'),
        ];

        $placedBalls = $bucket->placeBalls($balls);

        return response()->json(['message' => 'Balls placed successfully.', 'placedBalls' => $placedBalls]);
    }
    

    
    public function calculateBallsForSpace($totalVolume)
{
    $balls = Ball::all();
    $ballsToPlace = [];

    foreach ($balls as $ball) {
        $availableBalls = $ball->qty; // Get the available quantity of balls for the color
        $requiredBalls = 0;

        while ($totalVolume >= $ball->volume && $availableBalls > 0) {
            $totalVolume -= $ball->volume;
            $requiredBalls++;
            $availableBalls--;
        }

        if ($requiredBalls > 0) {
            $ballsToPlace[] = [
                'color' => $ball->color,
                'quantity' => $requiredBalls,
                'volume' => $ball->volume
            ];
        }
    }

    return $ballsToPlace;
}



}
