<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Chapa;
use App\Models\ChapaItem;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChapaChapaItemsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_chapa_chapa_items(): void
    {
        $chapa = Chapa::factory()->create();
        $chapaItems = ChapaItem::factory()
            ->count(2)
            ->create([
                'chapa_id' => $chapa->id,
            ]);

        $response = $this->getJson(
            route('api.chapas.chapa-items.index', $chapa)
        );

        $response->assertOk()->assertSee($chapaItems[0]->largura);
    }

    /**
     * @test
     */
    public function it_stores_the_chapa_chapa_items(): void
    {
        $chapa = Chapa::factory()->create();
        $data = ChapaItem::factory()
            ->make([
                'chapa_id' => $chapa->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.chapas.chapa-items.store', $chapa),
            $data
        );

        unset($data['chapa_id']);

        $this->assertDatabaseHas('chapa_items', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $chapaItem = ChapaItem::latest('id')->first();

        $this->assertEquals($chapa->id, $chapaItem->chapa_id);
    }
}
