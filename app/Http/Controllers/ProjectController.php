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
        $projects = $user->projects()->latest()->get();

        return view('projects.index', ['projects' => $projects]);
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
        // Memuat tugas yang berhubungan dengan proyek ini
        $project->load('tasks', 'members', 'owner');

        return view('projects.show', [
            'project' => $project
        ]);
    }
}
