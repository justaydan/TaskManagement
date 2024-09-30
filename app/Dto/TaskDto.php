<?php

namespace App\Dto;

use App\Http\Requests\CreateOrUpdateTaskRequest;

class TaskDto
{
    /** @var string */
    private string $name;

    /** @var string */
    private string $description;

    /**
     * @var int
     */
    private int $projectId;

    /**
     * @var int|null
     */
    private int|null $taskId;

    /**
     * @param CreateOrUpdateTaskRequest $request
     */
    public function __construct(CreateOrUpdateTaskRequest $request)
    {
        $this->name = $request->input('name');
        $this->description = $request->input('description');
        $this->projectId = $request->input('project_id');
        $this->taskId = $request->input('task_id');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @return int|null
     */
    public function getTaskId(): ?int
    {
        return $this->taskId;
    }


}
