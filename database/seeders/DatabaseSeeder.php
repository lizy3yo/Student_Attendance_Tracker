<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create the primary teacher account
        $teacher = User::firstOrCreate(
            ['email' => 'gonzales.asheera@gordoncollege.edu.ph'],
            [
                'name'        => 'Asheera Gonzales',
                'first_name'  => 'Asheera',
                'last_name'   => 'Gonzales',
                'password'    => Hash::make('TestPass123'),
            ]
        );

        // Seed additional teacher accounts
        User::firstOrCreate(
            ['email' => 'de.jesus.kharl@gordoncollege.edu.ph'],
            [
                'name'        => 'Kharl De Jesus',
                'first_name'  => 'Kharl',
                'last_name'   => 'De Jesus',
                'password'    => Hash::make('TestPass123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'teacher@attendtrack.com'],
            [
                'name'        => 'Demo Teacher',
                'first_name'  => 'Demo',
                'last_name'   => 'Teacher',
                'password'    => Hash::make('password'),
            ]
        );


        // Demo students
        $studentsData = [
            ['student_id_number' => '202400001', 'first_name' => 'Maria',   'last_name' => 'Santos',    'section' => 'BSIT-3A', 'email' => 'maria@school.edu'],
            ['student_id_number' => '202400002', 'first_name' => 'Juan',    'last_name' => 'Dela Cruz', 'section' => 'BSIT-3A', 'email' => 'juan@school.edu'],
            ['student_id_number' => '202400003', 'first_name' => 'Ana',     'last_name' => 'Reyes',     'section' => 'BSIT-3A', 'email' => null],
            ['student_id_number' => '202400004', 'first_name' => 'Carlo',   'last_name' => 'Garcia',    'section' => 'BSIT-3A', 'email' => null],
            ['student_id_number' => '202400005', 'first_name' => 'Liza',    'last_name' => 'Torres',    'section' => 'BSIT-3B', 'email' => 'liza@school.edu'],
            ['student_id_number' => '202400006', 'first_name' => 'Miguel',  'last_name' => 'Bautista',  'section' => 'BSIT-3B', 'email' => null],
            ['student_id_number' => '202400007', 'first_name' => 'Claire',  'last_name' => 'Mendoza',   'section' => 'BSIT-3B', 'email' => null],
            ['student_id_number' => '202400008', 'first_name' => 'Ramon',   'last_name' => 'Villanueva','section' => 'BSCS-2A', 'email' => null],
            ['student_id_number' => '202400009', 'first_name' => 'Sophia',  'last_name' => 'Aquino',    'section' => 'BSCS-2A', 'email' => 'sophia@school.edu'],
            ['student_id_number' => '202400010', 'first_name' => 'Patrick', 'last_name' => 'Castillo',  'section' => 'BSCS-2A', 'email' => null],
        ];

        $students = [];
        foreach ($studentsData as $data) {
            $data['user_id'] = $teacher->id;
            $students[] = Student::firstOrCreate(
                ['student_id_number' => $data['student_id_number']],
                $data
            );
        }

        // Demo Classes
        $classesData = [
            [
                'class_name' => 'CHTM',
                'class_code' => '43455 - ITE324L',
                'year' => 2,
                'block' => 'B',
                'semester' => 'Second',
                'academic_year' => '2025-2026',
                'capacity' => 40,
            ],
            [
                'class_name' => 'Introduction to Programming',
                'class_code' => 'CS1-A',
                'year' => 1,
                'block' => 'A',
                'semester' => 'First',
                'academic_year' => '2025-2026',
                'capacity' => 35,
            ],
            [
                'class_name' => 'Web Development',
                'class_code' => 'BSIT-3A',
                'year' => 3,
                'block' => 'A',
                'semester' => 'First',
                'academic_year' => '2025-2026',
                'capacity' => 45,
            ],
        ];

        $classes = [];
        foreach ($classesData as $cData) {
            $cData['user_id'] = $teacher->id;
            $classes[] = SchoolClass::firstOrCreate(
                ['user_id' => $teacher->id, 'class_code' => $cData['class_code']],
                $cData
            );
        }

        // Enroll students to classes
        // 1. Enroll first 4 students to class 0 (CHTM - 4/40 capacity)
        for ($i = 0; $i < 4; $i++) {
            DB::table('class_student')->insertOrIgnore([
                'class_id' => $classes[0]->id,
                'student_id' => $students[$i]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Enroll next 3 students to class 1 (CS1-A)
        for ($i = 4; $i < 7; $i++) {
            DB::table('class_student')->insertOrIgnore([
                'class_id' => $classes[1]->id,
                'student_id' => $students[$i]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Enroll all 10 students to class 2 (BSIT-3A)
        for ($i = 0; $i < 10; $i++) {
            DB::table('class_student')->insertOrIgnore([
                'class_id' => $classes[2]->id,
                'student_id' => $students[$i]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed attendance for the past 7 days (both general and class-specific)
        $statuses = ['present', 'present', 'present', 'present', 'absent', 'late', 'present'];
        for ($day = 6; $day >= 0; $day--) {
            $date = now()->subDays($day)->toDateString();
            
            // Seed general attendance
            foreach ($students as $idx => $student) {
                Attendance::firstOrCreate(
                    ['student_id' => $student->id, 'class_id' => null, 'date' => $date],
                    [
                        'user_id' => $teacher->id,
                        'status'  => $statuses[($idx + $day) % count($statuses)],
                        'remarks' => null,
                    ]
                );
            }

            // Seed class-specific attendance for CHTM (classes[0])
            // Enrolled students: $students[0..3]
            for ($i = 0; $i < 4; $i++) {
                Attendance::firstOrCreate(
                    ['student_id' => $students[$i]->id, 'class_id' => $classes[0]->id, 'date' => $date],
                    [
                        'user_id' => $teacher->id,
                        'status'  => $statuses[($i + $day + 1) % count($statuses)],
                        'remarks' => null,
                    ]
                );
            }

            // Seed class-specific attendance for CS1-A (classes[1])
            // Enrolled students: $students[4..6]
            for ($i = 4; $i < 7; $i++) {
                Attendance::firstOrCreate(
                    ['student_id' => $students[$i]->id, 'class_id' => $classes[1]->id, 'date' => $date],
                    [
                        'user_id' => $teacher->id,
                        'status'  => $statuses[($i + $day + 2) % count($statuses)],
                        'remarks' => null,
                    ]
                );
            }

            // Seed class-specific attendance for BSIT-3A (classes[2])
            // Enrolled students: all 10
            for ($i = 0; $i < 10; $i++) {
                Attendance::firstOrCreate(
                    ['student_id' => $students[$i]->id, 'class_id' => $classes[2]->id, 'date' => $date],
                    [
                        'user_id' => $teacher->id,
                        'status'  => $statuses[($i + $day + 3) % count($statuses)],
                        'remarks' => null,
                    ]
                );
            }
        }
    }
}
