<?php

namespace App\Http\Controllers;

use App\Models\ProjectFile;
use Illuminate\Http\Request;

class FileCommentController extends Controller
{
    public function store(Request $request, ProjectFile $file)
    {
        $request->validate(['body' => 'required|string']);

        $file->comments()->create([
            'body' => $request->body,
            'user_id' => $request->user()->id, // <-- PERUBAHAN DI SINI
        ]);

        return back()->with('status', 'Komentar ditambahkan.');
    }
}
