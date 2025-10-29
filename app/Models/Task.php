<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'project_id',
        'user_id',
        'due_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date', // <-- TAMBAHKAN BLOK INI
    ];

    /**
     * Mendefinisikan relasi bahwa sebuah tugas (Task) dimiliki oleh sebuah proyek (Project).
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Mendefinisikan relasi bahwa sebuah tugas (Task) ditugaskan kepada seorang pengguna (User).
     * Ini bisa null jika belum ada penanggung jawab.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest(); // Tampilkan komentar terbaru di atas
    }
}
