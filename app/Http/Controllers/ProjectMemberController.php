<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function store(Request $request, Project $project)
    {
        // Validasi: Pastikan email diisi dan valid
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $validated['email'])->first();

        // Jika user tidak ditemukan, kembali dengan error
        if (!$user) {
            return back()->withErrors(['email' => 'Pengguna dengan email ini tidak ditemukan.']);
        }

        // Cek apakah user sudah menjadi anggota
        if ($project->members()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['email' => 'Pengguna ini sudah menjadi anggota proyek.']);
        }

        // Tambahkan user ke dalam proyek
        $project->members()->attach($user->id);

        return redirect()->route('projects.show', $project)->with('status', 'Anggota berhasil ditambahkan!');
    }

    /**
     * Menghapus anggota dari proyek.
     */
    public function destroy(Project $project, User $user)
    {
        // 1. Otorisasi: Pastikan user yang login adalah pemilik proyek
        $this->authorize('removeMember', $project);

        // 2. Lepaskan relasi user dari proyek di pivot table
        $project->members()->detach($user->id);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('status', 'Anggota tim berhasil dihapus.');
    }
}
