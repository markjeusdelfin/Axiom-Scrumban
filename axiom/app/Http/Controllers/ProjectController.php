<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = \App\Models\Project::with('owner')->latest()->get();
        return inertia('Projects/Index', ['projects' => $projects]);
    }

    public function create()
    {
        return inertia('Projects/Create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,on_hold,completed',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = $request->user()->projects()->create($validated);
        ActivityLog::record('created', $project);

        return redirect()->route('projects.index');
    }

    public function show(\App\Models\Project $project)
    {
        return inertia('Projects/Show', [
            'project' => $project->load(['owner', 'members.user', 'tasks.assignee']),
            'users'   => \App\Models\User::all(),
        ]);
    }

    public function kanban(\App\Models\Project $project)
    {
        return inertia('Projects/Kanban', [
            'project' => $project->load(['members.user', 'tasks.assignee']),
        ]);
    }

    public function gantt(\App\Models\Project $project)
    {
        return inertia('Projects/Gantt', [
            'project' => $project->load(['tasks.assignee', 'tasks.dependencies']),
        ]);
    }

    public function edit(\App\Models\Project $project)
    {
        return inertia('Projects/Edit', ['project' => $project]);
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Project $project)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,on_hold,completed',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $old = $project->only(array_keys($validated));
        $project->update($validated);
        ActivityLog::record('updated', $project, ['old' => $old, 'new' => $validated]);

        return redirect()->route('projects.index');
    }

    public function destroy(\App\Models\Project $project)
    {
        ActivityLog::record('deleted', $project, ['name' => $project->name]);
        $project->delete();
        return redirect()->route('projects.index');
    }
}
