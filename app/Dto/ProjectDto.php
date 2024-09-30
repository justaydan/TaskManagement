<?php

namespace App\Dto;

use App\Http\Requests\CreateOrUpdateProjectRequest;

class ProjectDto
{
    /** @var string */
    private string $name;

    /** @var string */
    private string $description;

    private int|null $projectId;


    /**
     * @param CreateOrUpdateProjectRequest $request
     */
    public function __construct(CreateOrUpdateProjectRequest $request)
    {
        $this->name = $request->input('name');
        $this->description = $request->input('description');
        $this->projectId = $request->input('id');

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

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }


}
