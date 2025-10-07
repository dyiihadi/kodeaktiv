<?php

namespace App\Http\Controllers;

use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Project;

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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
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
}
