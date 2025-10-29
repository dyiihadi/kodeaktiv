<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Menyimpan tugas baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        // Otorisasi: Pastikan user yang login boleh menambah tugas ke proyek ini
        $this->authorize('update', $project);

        // Buat tugas yang sudah terelasi dengan proyek
        $project->tasks()->create([
            'title' => $validated['title'],
        ]);

        // --- INI BAGIAN PERBAIKANNYA ---

        // Alih-alih hanya `return back()`, kita redirect kembali ke halaman `show`
        // Ini akan memaksa ProjectController@show untuk berjalan lagi dan mengambil
        // semua data tugas yang sudah diperbarui, termasuk tugas yang baru dibuat.
        return redirect()->route('projects.show', $project)
            ->with('status', 'Tugas baru berhasil ditambahkan.');
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

    /**
     * Memperbarui tugas yang ada.
     */
    public function update(Request $request, Task $task)
    {
        // 1. Otorisasi
        $this->authorize('update', $task);

        // 2. Validasi
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        // 3. Update data
        $task->update($validated);

        return back()->with('status', 'Tugas berhasil diperbarui!');
    }

    /**
     * Menghapus tugas.
     */
    public function destroy(Task $task)
    {
        // 1. Otorisasi
        $this->authorize('delete', $task);

        // 2. Hapus data
        $task->delete();

        return back()->with('status', 'Tugas berhasil dihapus.');
    }
}
