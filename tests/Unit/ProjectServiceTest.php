<?php

namespace Tests\Unit;

use App\Dto\ProjectDto;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class ProjectServiceTest extends TestCase
{

    private ProjectService $projectService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectService = new ProjectService();
    }

    public function testCreateProject(): void
    {
        $userMock = Mockery::mock(User::class);
        $projectsMock = Mockery::mock(HasMany::class);
        $userMock->shouldReceive('projects')->andReturn($projectsMock);
        $projectsMock->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Test Project',
                'description' => 'Test Description',
            ])
            ->andReturn(new Project());
        Auth::shouldReceive('user')->andReturn($userMock);
        $dto = Mockery::mock(ProjectDto::class);
        $dto->shouldReceive('getName')->andReturn('Test Project');
        $dto->shouldReceive('getDescription')->andReturn('Test Description');

        $response = $this->projectService->store($dto);

        $this->assertInstanceOf(Project::class, $response);
    }

}
