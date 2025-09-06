<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'title',
        'status',
        'scheduled_at',
        'duration_minutes',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_CANCELLED = 'cancelled';

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function endAt(): \Carbon\CarbonInterface
    {
        return $this->scheduled_at->copy()->addMinutes($this->duration_minutes ?? 60);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AppointmentHistory::class)->orderByDesc('created_at');
    }
}
