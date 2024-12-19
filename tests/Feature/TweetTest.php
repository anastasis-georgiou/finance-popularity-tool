<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Tweet;
use App\Models\Handle;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_new_tweet()
    {
        $handle = Handle::factory()->create();
        $data = [
            'handle_id' => $handle->id,
            'tweet_id' => '12345',
            'content' => 'This is a test tweet',
            'processed' => false
        ];

        $response = $this->postJson('/api/tweets', $data);
        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data' => ['id', 'handle_id', 'tweet_id']]);
        $this->assertDatabaseHas('tweets', ['tweet_id' => '12345']);
    }

    #[Test]
    public function it_can_list_tweets()
    {
        Tweet::factory()->count(3)->create();
        $response = $this->getJson('/api/tweets');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_single_tweet()
    {
        $tweet = Tweet::factory()->create();
        $response = $this->getJson("/api/tweets/{$tweet->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['tweet_id' => $tweet->tweet_id]);
    }

    #[Test]
    public function it_can_update_a_tweet()
    {
        $tweet = Tweet::factory()->create(['content' => 'Old content']);
        $response = $this->putJson("/api/tweets/{$tweet->id}", ['content' => 'New content', 'processed' => true]);

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => 'New content', 'processed' => true]);
        $this->assertDatabaseHas('tweets', ['content' => 'New content', 'processed' => true]);
    }

    #[Test]
    public function it_can_delete_a_tweet()
    {
        $tweet = Tweet::factory()->create();
        $response = $this->deleteJson("/api/tweets/{$tweet->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tweets', ['id' => $tweet->id]);
    }
}
