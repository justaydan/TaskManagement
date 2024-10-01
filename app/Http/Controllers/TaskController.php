<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateTaskRequest;
use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{

    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * @param CreateOrUpdateTaskRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrUpdateTaskRequest $request)
    {
        try {
            $this->taskService->store($request->toDto());
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeleteTaskRequest $request)
    {
        return response()->json($this->taskService->find($request->task_id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateOrUpdateTaskRequest $request)
    {
        try {
            $dto = $request->toDto();
            $this->taskService->update($dto->getTaskId(), [
                'name' => $dto->getName(),
                'description' => $dto->getDescription(),
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }

    public function updateStatus(TaskRequest $request, Project $project, Task $task)
    {
        try {
            $this->taskService->update($task->id, ['status' => $request->status]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param DeleteTaskRequest $request
     * @return JsonResponse
     */
    public function destroy(DeleteTaskRequest $request)
    {
        try {
            $this->taskService->delete($request->task_id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
