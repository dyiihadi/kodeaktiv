<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project; // Pastikan model Project di-import
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            // Validasi: Tenggat waktu harus setelah tanggal mulai
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Pastikan user memiliki akses ke project ini sebelum membuat task
        $project = Project::findOrFail($request->project_id);

        // Cek otorisasi (User harus pemilik atau anggota)
        if ($project->user_id !== Auth::id() && !$project->members->contains(Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        // Set status default
        $validated['status'] = 'To Do';

        Task::create($validated);

        return back()->with('status', 'Tugas berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        // Otorisasi via Policy (pastikan TaskPolicy sudah ada atau cek manual)
        $this->authorize('update', $task->project);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:To Do,In Progress,Done',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $task->update($validated);

        return back()->with('status', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task->project);

        $task->delete();

        return back()->with('status', 'Tugas berhasil dihapus!');
    }
}
