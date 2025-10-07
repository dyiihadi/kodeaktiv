<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\FileCommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk mengelola proyek
    Route::resource('/projects', ProjectController::class);

    // Rute untuk menyimpan tugas baru
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Rute untuk menambah anggota ke proyek
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');

    // Rute untuk menghapus anggota dari proyek
    Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])->name('projects.members.destroy');

    // Rute untuk update status tugas via drag-and-drop
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Rute untuk menyimpan komentar baru pada tugas
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::post('/projects/{project}/files', [ProjectFileController::class, 'store'])->name('projects.files.store');

    Route::post('/files/{file}/comments', [FileCommentController::class, 'store'])->name('files.comments.store');
});

require __DIR__ . '/auth.php';
