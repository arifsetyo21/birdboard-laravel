<?php

namespace App\Http\Controllers;

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
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        // presist
        /* NOTE create relation without eloquent accosiation feature */
        // $attributes['owner_id'] = auth()->id();
        // Project::create($attributes);

        /* NOTE Create relation with accosiation feature */
        auth()->user()->projects()->create($attributes);

        // redirect
        return redirect('/projects');
    }

    public function show(Project $project)
    {
        /* NOTE with model binding */
        // $project = Project::findOrFail(request('project'));

        // if (auth()->id()  !== (int) $project->owner_id) {
        /* NOTE isNot() method is for checking is true */
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }
}
