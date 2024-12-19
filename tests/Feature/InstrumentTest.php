<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Instrument;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstrumentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_new_instrument()
    {
        $data = ['symbol' => '$TEST'];
        $response = $this->postJson('/api/instruments', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data' => ['id', 'symbol']]);
        $this->assertDatabaseHas('instruments', ['symbol' => '$TEST']);
    }

    #[Test]
    public function it_can_list_instruments()
    {
        Instrument::factory()->count(3)->create();
        $response = $this->getJson('/api/instruments');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_single_instrument()
    {
        $instrument = Instrument::factory()->create(['symbol' => '$ABC']);
        $response = $this->getJson("/api/instruments/{$instrument->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['symbol' => '$ABC']);
    }

    #[Test]
    public function it_can_update_an_instrument()
    {
        $instrument = Instrument::factory()->create(['symbol' => '$OLD']);
        $response = $this->putJson("/api/instruments/{$instrument->id}", ['symbol' => '$NEW']);

        $response->assertStatus(200)
            ->assertJsonFragment(['symbol' => '$NEW']);
        $this->assertDatabaseHas('instruments', ['symbol' => '$NEW']);
    }

    #[Test]
    public function it_can_delete_an_instrument()
    {
        $instrument = Instrument::factory()->create();
        $response = $this->deleteJson("/api/instruments/{$instrument->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('instruments', ['id' => $instrument->id]);
    }
}
