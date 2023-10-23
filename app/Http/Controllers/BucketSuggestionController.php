<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bucket;
use App\Models\Ball;

class BucketSuggestionController extends Controller
{
    public function showForm()
    {
        return view('bucket_suggestion_form');
    }

    public function calculateSuggestions(Request $request)
    {
        $request->validate([
            'red_balls' => 'required|integer|min:0',
            'blue_balls' => 'required|integer|min:0',
        ]);

        $redBallSize = 5;  // Size of a red ball in cubic inches
        $blueBallSize = 3; // Size of a blue ball in cubic inches

        $totalRedBallSize = $request->input('red_balls') * $redBallSize;
        $totalBlueBallSize = $request->input('blue_balls') * $blueBallSize;

        $totalBallSize = $totalRedBallSize + $totalBlueBallSize;

        $bucketsRequired = ceil($totalBallSize / Bucket::getMaxCapacity());

        $extraRedBalls = $request->input('red_balls') - ($bucketsRequired * Bucket::getMaxCapacity() / $redBallSize);
        $extraBlueBalls = $request->input('blue_balls') - ($bucketsRequired * Bucket::getMaxCapacity() / $blueBallSize);

        if ($extraRedBalls > 0 || $extraBlueBalls > 0) {
            $extraBallsMessage = '';

            if ($extraRedBalls > 0) {
                $extraBallsMessage .= "{$extraRedBalls} Red Ball" . ($extraRedBalls > 1 ? 's' : '') . ", ";
            }

            if ($extraBlueBalls > 0) {
                $extraBallsMessage .= "{$extraBlueBalls} Blue Ball" . ($extraBlueBalls > 1 ? 's' : '') . ", ";
            }

            // $extraBallsMessage = rtrim($extraBallsMessage, ', ');
                $extraBallsMessage = '';

                if ($extraRedBalls > 0 || $extraBlueBalls > 0) {
                    $extraBallsMessage .= "{$extraRedBalls} Red Ball" . ($extraRedBalls > 1 ? 's' : '') . ", ";
                    $extraBallsMessage .= "{$extraBlueBalls} Blue Ball" . ($extraBlueBalls > 1 ? 's' : '') . ", ";
                    $extraBallsMessage = rtrim($extraBallsMessage, ', ');
                }
                dd($extraBallsMessage); // Dump the extraBallsMessage for debugging

                return view('bucket_suggestion_result', compact('bucketsRequired', 'extraBallsMessage'));
            }

        //     return view('bucket_suggestion_result', compact('bucketsRequired', 'extraBallsMessage'));
        // }

        return view('bucket_suggestion_result', compact('bucketsRequired'));
    }
}

