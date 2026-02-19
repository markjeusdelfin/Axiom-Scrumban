<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Events\TaskUpdated;
use App\Events\TaskMoved;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tasks are viewed via Projects
        return redirect()->route('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(\Illuminate\Http\Request $request)
    {
        $project_id = $request->input('project_id');
        $project = \App\Models\Project::with('members.user')->findOrFail($project_id);
        
        return inertia('Tasks/Create', [
            'project' => $project,
            'members' => $project->members->map(fn($m) => $m->user),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:not_started,in_progress,completed,blocked',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        \App\Models\Task::create($validated);

        return redirect()->route('projects.show', $validated['project_id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Task $task)
    {
        return inertia('Tasks/Show', ['task' => $task->load('project', 'assignee')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Task $task)
    {
        $project = $task->project->load('members.user');
        
        $availableTasks = \App\Models\Task::where('project_id', $task->project_id)
            ->where('id', '!=', $task->id)
            ->get();

        return inertia('Tasks/Edit', [
            'task'           => $task->load('dependencies', 'comments'),
            'project'        => $project,
            'members'        => $project->members->map(fn($m) => $m->user),
            'availableTasks' => $availableTasks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, \App\Models\Task $task)
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:not_started,in_progress,completed,blocked',
            'priority' => 'required|in:low,medium,high,urgent',
            'progress_percent' => 'integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'exists:tasks,id',
        ]);

        // Validation: Block start if dependencies not complete
        if (in_array($validated['status'], ['in_progress', 'completed']) && $task->status === 'not_started') {
            $incompleteDependencies = $task->dependencies()->where('status', '!=', 'completed')->exists();
            if ($incompleteDependencies) {
                return back()->withErrors(['status' => 'Cannot start task until all dependencies are completed.']);
            }
        }

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
            $validated['progress_percent'] = 100;
        }

        $task->update(\Illuminate\Support\Arr::except($validated, ['dependencies']));

        if (isset($validated['dependencies'])) {
            $task->dependencies()->sync($validated['dependencies']);
        }

        broadcast(new TaskUpdated($task))->toOthers();

        return redirect()->route('projects.show', $task->project_id);
    }

    public function updateStatus(\Illuminate\Http\Request $request, \App\Models\Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,blocked',
        ]);
        
        // Dependency Check
        if (in_array($validated['status'], ['in_progress', 'completed']) && $task->status === 'not_started') {
            $incompleteDependencies = $task->dependencies()->where('status', '!=', 'completed')->exists();
            if ($incompleteDependencies) {
                return response()->json(['message' => 'Cannot start task until dependencies are completed.'], 422);
            }
        }

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $task->completed_at = now();
            $task->progress_percent = 100;
        }

        $oldStatus = $task->status;
        $task->update(['status' => $validated['status']]);

        broadcast(new TaskMoved($task, $oldStatus, $validated['status']))->toOthers();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Task $task)
    {
        $projectId = $task->project_id;
        $task->delete();
        return redirect()->route('projects.show', $projectId);
    }
}
