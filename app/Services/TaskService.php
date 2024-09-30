<?php

namespace App\Services;

use App\Constant\StatusConstant;
use App\Dto\TaskDto;
use App\Models\Project;
use App\Models\Task;

class TaskService
{

    public function store(TaskDto $dto)
    {
        $project = Project::query()->find($dto->getProjectId());
        $project->tasks()->create(['name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'status' => StatusConstant::PENDING
        ]);
    }

    public function update(TaskDto $dto)
    {
        Task::query()->find($dto->getTaskId())
            ->update([
                'name' => $dto->getName(),
                'description' => $dto->getDescription(),
            ]);
    }

}
