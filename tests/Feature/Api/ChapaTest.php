<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Chapa;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChapaTest extends TestCase
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
    public function it_gets_chapas_list(): void
    {
        $chapas = Chapa::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.chapas.index'));

        $response->assertOk()->assertSee($chapas[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_chapa(): void
    {
        $data = Chapa::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.chapas.store'), $data);

        $this->assertDatabaseHas('chapas', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_chapa(): void
    {
        $chapa = Chapa::factory()->create();

        $data = [
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('api.chapas.update', $chapa), $data);

        $data['id'] = $chapa->id;

        $this->assertDatabaseHas('chapas', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_chapa(): void
    {
        $chapa = Chapa::factory()->create();

        $response = $this->deleteJson(route('api.chapas.destroy', $chapa));

        $this->assertDeleted($chapa);

        $response->assertNoContent();
    }
}
