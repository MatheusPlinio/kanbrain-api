<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Requests\Api\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectsResource;
use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function __construct(protected ProjectRepositoryInterface $projectRepository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProjectsResource::collection(Project::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectRepository->store(
            $request->validated(),
            $request->user()
        );

        return new ProjectsResource($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return new ProjectsResource($project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project = $this->projectRepository->update($request->validated(), $project);

        return new ProjectsResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->projectRepository->destroy($project);

        return response()->noContent();
    }
}
