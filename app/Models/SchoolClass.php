<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SchoolClass
 *
 * @property int $id
 * @property int $user_id
 * @property string $class_name
 * @property string $class_code
 * @property int $year
 * @property string $block
 * @property string $semester
 * @property string $academic_year
 * @property int $capacity
 */
class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'user_id',
        'class_name',
        'class_code',
        'year',
        'block',
        'semester',
        'academic_year',
        'capacity',
    ];

    // Students enrolled in this class
    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id')
                    ->withTimestamps();
    }

    // Teacher who owns this class
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Attendance records for this class
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }
}
