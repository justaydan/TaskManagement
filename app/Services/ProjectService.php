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
        $this->findProject($dto->getProjectId())->update([
            'name' => $dto->getName(),
            'description' => $dto->getDescription()
        ]);
    }

    public function getTasks(int $projectId)
    {
        return $this->findProject($projectId)->load('tasks');
    }

    public function delete(int $projectId)
    {
        $this->findProject($projectId)->delete();
    }

    public function findProject(int $projectId)
    {
        return Project::query()->find($projectId);
    }

    public function get()
    {
        return auth()->user()->projects()->get();
    }
}
