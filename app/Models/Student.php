<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $reportTotal
 * @property int|null $reportPresent
 * @property int|null $reportAbsent
 * @property int|null $reportLate
 * @property float|null $reportPercent
 *
 * Dynamic properties added at runtime (used by controller with `withCount`):
 * @property int|null $period_total
 * @property int|null $period_present
 * @property float|null $period_pct
 *
 * Accessors:
 * @property-read string $full_name
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id_number',
        'first_name',
        'last_name',
        'section',
        'email',
        'user_id',
    ];

    // Full name accessor
    public function getFullNameAttribute(): string
    {
        return "{$this->last_name}, {$this->first_name}";
    }

    // Teacher who manages this student
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Classes this student is enrolled in
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
                    ->withTimestamps();
    }

    // Attendance percentage
    public function attendancePercentage(): float
    {
        $total = $this->attendances()->count();
        if ($total === 0) return 0;
        $present = $this->attendances()->whereIn('status', ['present', 'late'])->count();
        return round(($present / $total) * 100, 2);
    }

    // Today's attendance status (null if not marked)
    public function todayStatus(): ?string
    {
        $record = $this->attendances()->where('date', today()->toDateString())->first();
        return $record?->status;
    }
}
