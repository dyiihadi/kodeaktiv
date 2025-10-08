<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectFileController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Maksimal 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store('project_files', 'public');

        $project->files()->create([
            'user_id' => $request->user()->id, // <-- PERUBAHAN DI SINI
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
        ]);

        return back()->with('status', 'File berhasil diunggah!');
    }

    public function destroy(ProjectFile $file)
    {
        // Gunakan policy untuk otorisasi
        $this->authorize('delete', $file);

        // Hapus file dari storage
        Storage::disk('public')->delete($file->path);

        // Hapus record file dari database
        $file->delete();

        return back()->with('status', 'File berhasil dihapus!');
    }
}
