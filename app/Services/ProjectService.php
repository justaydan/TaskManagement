<?php

namespace App\Services;

use App\Dto\ProjectDto;
use App\Models\Project;

class ProjectService
{

    public function store(ProjectDto $dto)
    {
        return auth()->user()->projects()->create([
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
        ]);
    }

    public function update(ProjectDto $dto)
    {
        Project::query()->find($dto->getProjectId())->update([
            'name' => $dto->getName(),
            'description' => $dto->getDescription()
        ]);
    }

    public function getTasks(int $projectId)
    {
        return Project::query()->find($projectId)->load('tasks');
    }

    public function delete(int $projectId)
    {
        Project::query()->find($projectId)->delete();
    }

    public function findProject(int $projectId)
    {
        return Project::query()->find($projectId);
    }
}
