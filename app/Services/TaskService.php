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

    public function update(int $taskId, array $data)
    {
        Task::query()->find($taskId)
            ->update($data);
    }

    public function delete(int $taskId)
    {
        Task::query()->find($taskId)->delete();
    }

    public function find(int $taskId)
    {
        return Task::query()->find($taskId);
    }

}
