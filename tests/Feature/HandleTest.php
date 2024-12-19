<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Handle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class HandleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_new_handle()
    {
        $response = $this->postJson('/api/handles', [
            'handle' => '@test_handle',
            'crawling_freq' => 1800
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data' => ['id', 'handle']]);
        $this->assertDatabaseHas('handles', ['handle' => '@test_handle']);
    }

    #[Test]
    public function it_can_list_handles()
    {
        Handle::factory()->count(3)->create();

        $response = $this->getJson('/api/handles');
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_single_handle()
    {
        $handle = Handle::factory()->create();
        $response = $this->getJson("/api/handles/{$handle->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['handle' => $handle->handle]);
    }

    #[Test]
    public function it_can_update_a_handle()
    {
        $handle = Handle::factory()->create(['handle' => '@old_handle']);
        $response = $this->putJson("/api/handles/{$handle->id}", ['handle' => '@new_handle']);
        $response->assertStatus(200)
            ->assertJsonFragment(['handle' => '@new_handle']);
    }

    #[Test]
    public function it_can_delete_a_handle()
    {
        $handle = Handle::factory()->create();
        $response = $this->deleteJson("/api/handles/{$handle->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('handles', ['id' => $handle->id]);
    }
}
