<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    public function store()
    {
        // validate
        $attributes = request()->validate(['title' => 'required', 'description' => 'required']);

        // presist
        Project::create($attributes);

        // redirect
        return redirect('/projects');
    }
}
