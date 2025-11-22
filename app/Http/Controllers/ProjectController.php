<?php

namespace App\Http\Controllers;

use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Project;
use App\Models\Comment;
use App\Models\Task;

class ProjectController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Ambil proyek yang dimiliki oleh user
        $ownedProjects = $user->projects()->with('owner')->get();

        // 2. Ambil proyek di mana user menjadi anggota (diundang)
        $sharedProjects = $user->sharedProjects()->with('owner')->get();

        // 3. Gabungkan kedua daftar proyek tersebut
        $projects = $ownedProjects->merge($sharedProjects)->unique('id');

        return view('projects.index', [
            'projects' => $projects,
        ]);
    }

    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Menampilkan form untuk mengedit proyek.
     */
    public function edit(Project $project): View
    {
        $this->authorize('update', $project); // Otorisasi
        return view('projects.edit', ['project' => $project]);
    }

    /**
     * Memperbarui proyek di database.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        // Otorisasi tetap dilakukan untuk keamanan berlapis
        $this->authorize('update', $project);

        // Validasi input seperti biasa
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            // Validasi: due_date harus setelah atau sama dengan start_date jika start_date diisi
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Lakukan update HANYA pada field yang divalidasi
        $project->update($validated);

        return redirect(route('projects.show', $project))->with('status', 'Proyek berhasil diperbarui!');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            // Validasi: due_date harus setelah atau sama dengan start_date
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->projects()->create($validated);

        return redirect(route('projects.index'))->with('status', 'Proyek berhasil dibuat!');
    }

    public function show(Project $project): View
    {
        // Muat semua relasi yang dibutuhkan oleh view
        $project->load([
            'members',
            'owner',
            'files.uploader',
            'files.comments.author' // <-- Tambahkan ini
        ]);

        $tasks = $project->tasks()->with('comments.author')->get()->groupBy('status');

        return view('projects.show', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }
    /**
     * Menghapus proyek dari database.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project); // Otorisasi

        $project->delete();

        return redirect(route('projects.index'))->with('status', 'Proyek berhasil dihapus!');
    }

    public function dashboard(): View
    {
        $user = Auth::user();
        $ownedProjects = $user->projects;
        $sharedProjects = $user->sharedProjects;
        $allProjects = $ownedProjects->merge($sharedProjects)->unique('id');
        $projectIds = $allProjects->pluck('id');

        $allTasks = Task::whereIn('project_id', $projectIds)->get();
        $taskCounts = $allTasks->groupBy('status')->map(fn($group) => $group->count());

        // Mengambil 5 aktivitas terakhir (tugas atau komentar baru)
        $recentTasks = Task::whereIn('project_id', $projectIds)->latest()->take(3)->get();
        $recentComments = Comment::whereIn('task_id', $allTasks->pluck('id'))->with('task.project')->latest()->take(3)->get();

        $recentActivities = $recentTasks->concat($recentComments)->sortByDesc('created_at')->take(5);

        return view('dashboard', [
            'totalProjects' => $allProjects->count(),
            'totalTasks' => $allTasks->count(),
            'tasksDone' => $taskCounts->get('Done', 0),
            'recentActivities' => $recentActivities,
        ]);
    }
}
