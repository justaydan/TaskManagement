<?php

namespace Tests\Feature;

use App\Constant\URLConstant;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UpdateProjectTest extends TestCase
{
    private array $body;
    private Project $project;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
        $this->body = ['name' => $this->faker->name, 'description' => $this->faker->text];
    }

    public function testOnSuccess()
    {
        Auth::LoginUsingId($this->user->id);
        $response = $this->sendRequest();
        $response->assertOk()->assertJsonStructure(['success']);
    }

    public function testUnathorized()
    {
        $response = $this->sendRequest();
        $response->assertUnauthorized();
    }

    public function testOnEmptyBody()
    {
        Auth::LoginUsingId($this->user->id);
        $this->body = [];
        $response = $this->sendRequest();
        $response->assertUnprocessable()->assertJsonValidationErrors(['name', 'description']);
    }

    /*
     * Trying to update another user's project
     */
    public function testOnFailure()
    {
        Auth::LoginUsingId($this->user->id);
        $project = Project::factory()->create(['user_id' => User::factory()->create()->id]);
        $this->project->id = $project->id;
        $this->sendRequest()->assertUnprocessable()->assertJsonValidationErrors(['id']);
    }

    /**
     * Trying to update not existing project
     */
    public function testOnWrongProject()
    {
        Auth::LoginUsingId($this->user->id);
        $this->project->id = 3;
        $this->sendRequest()->assertUnprocessable()->assertJsonValidationErrors(['id']);
    }

    public function sendRequest()
    {
        return $this->putJson(route('projects.update', ['project' => $this->project->id]), $this->body);
    }
}
