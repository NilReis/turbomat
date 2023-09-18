<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Chapa;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChapaControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_chapas(): void
    {
        $chapas = Chapa::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('chapas.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.chapas.index')
            ->assertViewHas('chapas');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_chapa(): void
    {
        $response = $this->get(route('chapas.create'));

        $response->assertOk()->assertViewIs('app.chapas.create');
    }

    /**
     * @test
     */
    public function it_stores_the_chapa(): void
    {
        $data = Chapa::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('chapas.store'), $data);

        $this->assertDatabaseHas('chapas', $data);

        $chapa = Chapa::latest('id')->first();

        $response->assertRedirect(route('chapas.edit', $chapa));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_chapa(): void
    {
        $chapa = Chapa::factory()->create();

        $response = $this->get(route('chapas.show', $chapa));

        $response
            ->assertOk()
            ->assertViewIs('app.chapas.show')
            ->assertViewHas('chapa');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_chapa(): void
    {
        $chapa = Chapa::factory()->create();

        $response = $this->get(route('chapas.edit', $chapa));

        $response
            ->assertOk()
            ->assertViewIs('app.chapas.edit')
            ->assertViewHas('chapa');
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

        $response = $this->put(route('chapas.update', $chapa), $data);

        $data['id'] = $chapa->id;

        $this->assertDatabaseHas('chapas', $data);

        $response->assertRedirect(route('chapas.edit', $chapa));
    }

    /**
     * @test
     */
    public function it_deletes_the_chapa(): void
    {
        $chapa = Chapa::factory()->create();

        $response = $this->delete(route('chapas.destroy', $chapa));

        $response->assertRedirect(route('chapas.index'));

        $this->assertDeleted($chapa);
    }
}
