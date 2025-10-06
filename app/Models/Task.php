<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
