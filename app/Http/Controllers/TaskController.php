<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Membuat task baru yang langsung berelasi dengan project ini
        $project->tasks()->create($validated);

        return redirect()->route('projects.show', $project);
    }
}
