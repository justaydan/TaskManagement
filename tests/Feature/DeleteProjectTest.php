<?php

namespace Tests\Feature;


use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class DeleteProjectTest extends TestCase
{
    private Project $project;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
    }

    public function testDeleteProjectSuccessfully()
    {
        $this->actingAs($this->user);
        $this->sendRequest();
        $this->assertDatabaseMissing(Project::class, $this->project->toArray());
    }

    public function testDeleteProjectWhenNotFound()
    {
        $this->actingAs($this->user);
        $this->project->id = 999;
        $this->sendRequest()->assertUnprocessable()->assertJsonValidationErrors('id');
        $this->assertDatabaseMissing('projects', ['id' => 999]);
    }

    public function testDeleteProjectWhenNotAuthorized()
    {
        $this->sendRequest()->assertUnauthorized();
    }

    public function testDeleteAnotherUsersProject()
    {
        $this->actingAs($this->user);
        $this->project->id = Project::factory()->create(['user_id' => User::factory()->create()->id])->id;
        $this->sendRequest()->assertUnprocessable()->assertJsonValidationErrors('id');
    }

    public function sendRequest()
    {
        return $this->deleteJson(route('projects.delete', ['project' => $this->project->id]));
    }
}
