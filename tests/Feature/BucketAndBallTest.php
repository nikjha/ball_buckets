<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Bucket;
use App\Models\Ball;
use Illuminate\Http\Request;

class BucketAndBallTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_buckets()
    {
        $response = $this->post('/bucket/create', ['capacity' => 50]);

        $response->assertStatus(302);
        $this->assertCount(1, Bucket::all());
    }

    /** @test */
    public function user_can_create_balls()
    {
        $response = $this->post('/ball/create', ['color' => 'red', 'size' => 5]);

        dd($response->getContent());

        $response->assertStatus(302);
        $this->assertCount(1, Ball::all());
    }


    /** @test */
    public function user_can_get_bucket_suggestions()
    {
        $response = $this->post('/bucket-suggestion/calculate', ['red_balls' => 10, 'blue_balls' => 10]);

        $response->assertStatus(200);
        $response->assertViewIs('bucket_suggestion_result');
        $response->assertViewHas('bucketsRequired');
    }

    /** @test */
    public function extra_balls_are_handled_correctly()
    {
        $requestData = ['red_balls' => 30, 'blue_balls' => 25];

        $response = $this->post('/bucket-suggestion/calculate', $requestData);

        $response->assertStatus(200)
            ->assertViewIs('bucket_suggestion_result')
            ->assertViewHas('bucketsRequired');

        if (isset($requestData['red_balls']) && isset($requestData['blue_balls'])) {
            $expectedMessage = '5 Red Balls, 5 Blue Balls cannot be accommodated in any bucket since there is no available space.';
            $this->assertEquals($expectedMessage, $response->original['extraBallsMessage']);
        } else {
            $this->assertArrayNotHasKey('extraBallsMessage', $response->original);
        }
    }


}
