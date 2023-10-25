<?php
namespace App\Http\Controllers;
use App\Models\Bucket;
use App\Models\Ball;
use App\Models\Ball_Purchased;
use Illuminate\Http\Request;

class BucketController extends Controller
{
    public function index()
    {
        $buckets = Bucket::all();
        $balls = Ball::with('purchases')->get();

        foreach ($balls as $ball) {
            \DB::enableQueryLog();
            $purchasedQuantity = $ball->purchases->sum('qty');
            $ballsbought[] = [
                    'color' => $ball->color,
                    'qty' => $purchasedQuantity
                ];
        }
        $suggestions = [];
        

        if ($buckets->isNotEmpty()) {
            foreach ($buckets as $bucket) {
                $totalVolume = $bucket->volume;
                $suggestions[$bucket->id] = $this->calculateBallsForSpace($totalVolume);
            }
        }

        return view('buckets.index', compact('buckets', 'balls', 'suggestions','ballsbought'));
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
    public function ball_buy(Request $request)
    {
        $request->validate([
            'ball_id' => 'required|numeric',
            'qty' => 'required|numeric|min:1',
        ]);

        $ballId = $request->input('ball_id');
        $quantityToAdd = $request->input('qty');

        // Check if the ball exists
        $ball = Ball::find($ballId);

        if (!$ball) {
            return redirect()->route('buckets.index')->with('error', 'Ball not found.');
        }

        // Check if there is a previous purchase record for this ball
        $ballPurchased = Ball_Purchased::where('ball_id', $ballId)->first();

        if ($ballPurchased) {
            // If a previous purchase record exists, update the quantity
            $ballPurchased->qty += $quantityToAdd;
            $ballPurchased->save();
        } else {
            // If no previous purchase record exists, create a new one
            Ball_Purchased::create([
                'ball_id' => $ballId,
                'qty' => $quantityToAdd,
            ]);
        }

        return redirect()->route('buckets.index')->with('success', 'Ball Bought: ' . $quantityToAdd);
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

    
public function calculateBallsForSpace($totalVolume)
    {
        // Retrieve all available balls from the database
        $balls = Ball::all();
        $ballsToPlace = [];

        foreach ($balls as $ball) {
            $availableBalls = $ball->qty; // Get the available quantity of balls for the color
            $requiredBalls = 0;

            // Try to place balls until the total volume is filled or no more balls are available
            while ($totalVolume >= $ball->volume && $availableBalls > 0) {
                $totalVolume -= $ball->volume;
                $requiredBalls++;
                $availableBalls--;
            }

            // If any balls were placed, add them to the list
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
