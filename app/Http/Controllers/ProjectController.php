<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function store()
    {

        // validate
        $attributes = $this->validateRequest();

        // presist
        /* NOTE create relation without eloquent accosiation feature */
        // $attributes['owner_id'] = auth()->id();
        // Project::create($attributes);

        /* NOTE Create relation with accosiation feature */
        $project = auth()->user()->projects()->create($attributes);

        // redirect
        return redirect($project->path());
    }

    public function show(Project $project)
    {
        /* NOTE with model binding */
        // $project = Project::findOrFail(request('project'));

        // if (auth()->id()  !== (int) $project->owner_id) {
        /* NOTE isNot() method is for checking is true */
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request)
    {
        return redirect($request->save()->path());
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required:max:100',
            'notes' => 'nullable'
        ]);
    }
}
