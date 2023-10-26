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
        

        // return redirect()->route('buckets.index')->with('success', 'Bucket created successfully.');
        return response()->json([
                'message' => 'Bucket created successfully.'
            ]);
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
            #return redirect()->route('buckets.index')->with('error', 'Ball not found.');
            return response()->json([
                'error' => 'Error in Ball Bought'.$quantityToAdd
            ]);
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

        #return redirect()->route('buckets.index')->with('message', 'Ball Bought: ' . $quantityToAdd);
        return response()->json([
                'message' => 'Ball Bought'.$quantityToAdd
            ]);
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

    public function placeBalls(Request $request)
    {
        // Retrieve available space (volume) from the request
        $volume = $request->input('volume');
        // Retrieve ball colors and quantities from the form
        $ballsbought = $request->except('_token', 'volume');

        $remainingVolume = $volume;
        $placedBalls = [];
        $overflowBalls = [];
        $criteria = $this->calculateCriteriaValue($ballsbought);
        $bucketId = Bucket::fetchBucketId($criteria);

        foreach ($ballsbought as $color => $quantity) {
            if (!ctype_digit($quantity) || $quantity <= 0) {
                return response()->json(['error' => 'Invalid quantity for ' . $color]);
            }

            // Calculate total volume for this color based on quantity
            $ballVolume = $quantity * $this->getBallVolume($color);

            // Checking if the requested volume exceeds the available space
            if ($ballVolume > $remainingVolume) {
                // Handling overflow by storing the color and volume of the ball that didn't fit
                $overflowBalls[] = ['color' => $color, 'volume' => $ballVolume];
            } else {
                $this->storePlacedBalls($bucketId, $color, $ballVolume, $quantity);
                $remainingVolume -= $ballVolume;
                $placedBalls[$color] = $quantity;
            }
        }

        // Check if there are overflow balls
        if (count($overflowBalls) > 0) {
            return response()->json([
                'message' => 'Overflow: Some balls cannot be accommodated.',
                'overflowBalls' => $overflowBalls,
                'placedBalls' => $placedBalls,
                'remainingVolume' => $remainingVolume
            ]);
        }

        return response()->json([
            'message' => 'All balls placed successfully.',
            'placedBalls' => $placedBalls,
            'remainingVolume' => $remainingVolume
        ]);
    }


    public function getBallVolume($color)
    {
        $ball = Ball::where('color', $color)->first();

        if ($ball) {
            return $ball->volume;
        } else {
            return 0; 
        }
    }

    private function storePlacedBalls($color, $ballVolume, $quantity)
    {
        
        for ($i = 0; $i < $quantity; $i++) {
        Results::create([
            'ball_id' => $color,
            'no_of_balls' => $ballVolume,
        ]);
    }
    }
    private function calculateCriteriaValue($ballsbought)
    {
        $totalVolume = 0;

        foreach ($ballsbought as $color => $quantity) {
            if (!ctype_digit($quantity) || $quantity <= 0) {
                return response()->json(['error' => 'Invalid quantity for ' . $color]);
            }

            $ballVolume = $quantity * $this->getBallVolume($color);
            $totalVolume += $ballVolume;
        }

        return $totalVolume;
    }



}
