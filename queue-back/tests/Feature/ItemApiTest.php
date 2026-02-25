<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_item(): void
    {
        $response = $this->postJson('/api/items', [
            'title' => 'Hello',
            'content' => 'Some valid content here',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('items', [
            'title' => 'Hello',
            'state' => 'pending',
        ]);
    }

    public function test_can_review_item_and_sets_state_note_and_reviewed_at(): void
    {
        $item = Item::create([
            'title' => 'Hello',
            'content' => 'World content long enough',
            'state' => 'pending',
            'risk_score' => 10,
            'flags' => [],
            'review_note' => null,
            'reviewed_at' => null,
        ]);

        $response = $this->postJson("/api/items/{$item->id}/review", [
            'action' => 'approve',
            'note' => 'Looks good',
        ]);

        $response->assertOk()
            ->assertJson([
                'id' => $item->id,
                'state' => 'approved',
                'review_note' => 'Looks good',
            ]);

        $item->refresh();

        $this->assertEquals('approved', $item->state);
        $this->assertEquals('Looks good', $item->review_note);
        $this->assertNotNull($item->reviewed_at);
    }

    public function test_index_can_filter_search_and_sort(): void
    {
        Item::create([
            'title' => 'Alpha',
            'content' => 'Some content here',
            'state' => 'approved',
            'risk_score' => 10,
            'flags' => [],
        ]);

        Item::create([
            'title' => 'Bitcoin promo',
            'content' => 'Win big now!!!',
            'state' => 'pending',
            'risk_score' => 80,
            'flags' => ['spam_keyword'],
        ]);

        Item::create([
            'title' => 'Bitcoin low risk',
            'content' => 'Just a mention of bitcoin',
            'state' => 'pending',
            'risk_score' => 20,
            'flags' => [],
        ]);

        // pending + q=bitcoin + sort by risk desc -> first should be "Bitcoin promo"
        $response = $this->getJson('/api/items?state=pending&q=bitcoin&sort=risk_score&dir=desc');

        $response->assertOk();

        $data = $response->json();

        $this->assertCount(2, $data);
        $this->assertEquals('Bitcoin promo', $data[0]['title']);
        $this->assertEquals('pending', $data[0]['state']);
        $this->assertEquals(80, $data[0]['risk_score']);
    }
}
