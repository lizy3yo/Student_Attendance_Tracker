<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'user_id',
        'date',
        'status',
        'remarks',
        'time_in',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Owning student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Owning class
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Recording teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Status label with color
    public function statusBadge(): array
    {
        return match ($this->status) {
            'present' => ['label' => 'Present', 'color' => 'success'],
            'absent'  => ['label' => 'Absent',  'color' => 'danger'],
            'late'    => ['label' => 'Late',     'color' => 'warning'],
            default   => ['label' => 'Unknown',  'color' => 'secondary'],
        };
    }
}
