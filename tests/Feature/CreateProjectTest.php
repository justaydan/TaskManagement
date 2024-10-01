<?php

namespace Tests\Feature;

use App\Constant\URLConstant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    private array $body;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create();
        $this->body = ['name' => $this->faker->name, 'description' => $this->faker->text];
    }

    public function testOnSuccess()
    {
        Auth::LoginUsingId(1);
        $response = $this->sendRequest();
        $response->assertCreated()->assertJsonStructure(['success']);
        $this->assertDatabaseHas('projects', ['name' => $this->body['name'], 'description' => $this->body['description']]);
    }

    public function testUnathorized()
    {
        $response = $this->sendRequest();
        $response->assertUnauthorized();
    }

    public function testOnEmptyBody()
    {
        Auth::LoginUsingId(1);
        $this->body = [];
        $response = $this->sendRequest();
        $response->assertUnprocessable()->assertJsonValidationErrors(['name', 'description']);
    }

    public function sendRequest()
    {
        return $this->postJson(route('projects.store'), $this->body);
    }
}
