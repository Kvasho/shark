<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('user.pages.projects', [
            'projects' => Project::active()->ordered()->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $project = Project::active()->where('slug', $slug)->with('media')->firstOrFail();

        return view('user.pages.project-show', [
            'project' => $project,
            'relatedProjects' => Project::active()->ordered()->whereKeyNot($project->id)->take(2)->get(),
        ]);
    }
}
