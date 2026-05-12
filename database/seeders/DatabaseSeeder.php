<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a demo teacher account
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@attendtrack.com'],
            [
                'name'     => 'Demo Teacher',
                'password' => Hash::make('password'),
            ]
        );

        // Demo students
        $studentsData = [
            ['student_id_number' => '2024-00001', 'first_name' => 'Maria',   'last_name' => 'Santos',    'section' => 'BSIT-3A', 'email' => 'maria@school.edu'],
            ['student_id_number' => '2024-00002', 'first_name' => 'Juan',    'last_name' => 'Dela Cruz', 'section' => 'BSIT-3A', 'email' => 'juan@school.edu'],
            ['student_id_number' => '2024-00003', 'first_name' => 'Ana',     'last_name' => 'Reyes',     'section' => 'BSIT-3A', 'email' => null],
            ['student_id_number' => '2024-00004', 'first_name' => 'Carlo',   'last_name' => 'Garcia',    'section' => 'BSIT-3A', 'email' => null],
            ['student_id_number' => '2024-00005', 'first_name' => 'Liza',    'last_name' => 'Torres',    'section' => 'BSIT-3B', 'email' => 'liza@school.edu'],
            ['student_id_number' => '2024-00006', 'first_name' => 'Miguel',  'last_name' => 'Bautista',  'section' => 'BSIT-3B', 'email' => null],
            ['student_id_number' => '2024-00007', 'first_name' => 'Claire',  'last_name' => 'Mendoza',   'section' => 'BSIT-3B', 'email' => null],
            ['student_id_number' => '2024-00008', 'first_name' => 'Ramon',   'last_name' => 'Villanueva','section' => 'BSCS-2A', 'email' => null],
            ['student_id_number' => '2024-00009', 'first_name' => 'Sophia',  'last_name' => 'Aquino',    'section' => 'BSCS-2A', 'email' => 'sophia@school.edu'],
            ['student_id_number' => '2024-00010', 'first_name' => 'Patrick', 'last_name' => 'Castillo',  'section' => 'BSCS-2A', 'email' => null],
        ];

        $students = [];
        foreach ($studentsData as $data) {
            $data['user_id'] = $teacher->id;
            $students[] = Student::firstOrCreate(
                ['student_id_number' => $data['student_id_number']],
                $data
            );
        }

        // Seed attendance for the past 7 days
        $statuses = ['present', 'present', 'present', 'present', 'absent', 'late', 'present'];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            foreach ($students as $idx => $student) {
                Attendance::firstOrCreate(
                    ['student_id' => $student->id, 'date' => $date],
                    [
                        'user_id' => $teacher->id,
                        'status'  => $statuses[($idx + $i) % count($statuses)],
                        'remarks' => null,
                    ]
                );
            }
        }
    }
}
