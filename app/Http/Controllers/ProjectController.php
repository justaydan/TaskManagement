<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateProjectRequest;
use App\Http\Requests\EditOrDeleteProjectRequest;
use App\Services\ProjectService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth()->user()->projects()->get();
        return view('projects.index', compact('projects'));
    }


    /**
     * @param CreateOrUpdateProjectRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrUpdateProjectRequest $request): JsonResponse
    {
        try {
            $this->projectService->store($request->toDto());
            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * @param EditOrDeleteProjectRequest $request
     * @return JsonResponse
     */
    public function edit(EditOrDeleteProjectRequest $request): JsonResponse
    {
        return response()->json($this->projectService->findProject($request->id));
    }

    /**
     * @param CreateOrUpdateProjectRequest $request
     * @return JsonResponse
     */
    public function update(CreateOrUpdateProjectRequest $request): JsonResponse
    {
        try {
            $this->projectService->update($request->toDto());
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param EditOrDeleteProjectRequest $request
     * @return JsonResponse
     */
    public function destroy(EditOrDeleteProjectRequest $request): JsonResponse
    {
        try {
            $this->projectService->delete($request->id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param EditOrDeleteProjectRequest $request
     * @return Factory|View|Application
     */
    public function showTasks(EditOrDeleteProjectRequest $request): Factory|View|Application
    {
        $project = $this->projectService->getTasks($request->id);
        return view('projects.tasks', compact('project'));
    }
}
