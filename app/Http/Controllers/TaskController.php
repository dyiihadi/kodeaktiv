<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Validation\Rule;
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

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['To Do', 'In Progress', 'Done'])],
        ]);

        // Lakukan otorisasi di sini jika perlu (misal: pastikan user adalah anggota proyek)

        $task->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Status tugas berhasil diperbarui.']);
    }
}
