<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolClassTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected User $otherTeacher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create();
        $this->otherTeacher = User::factory()->create();
    }

    public function test_teacher_can_view_classes_index(): void
    {
        // Create classes for both teachers
        $class1 = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $class2 = SchoolClass::create([
            'user_id' => $this->otherTeacher->id,
            'class_name' => 'Algorithms',
            'class_code' => 'CS-201',
            'year' => 2,
            'block' => 'B',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 30,
        ]);

        $response = $this->actingAs($this->teacher)->get('/classes');

        $response->assertOk();
        $response->assertSee('Web Development');
        $response->assertSee('IT-301');
        $response->assertDontSee('Algorithms'); // Should not see another teacher's class
    }

    public function test_teacher_can_create_class(): void
    {
        $response = $this->actingAs($this->teacher)
            ->post('/classes', [
                'class_name' => 'Database Systems',
                'class_code' => 'CS-302',
                'year' => '3',
                'block' => 'B',
                'semester' => 'Second',
                'academic_year' => '2025-2026',
                'capacity' => '35',
            ]);

        $response->assertRedirect('/classes');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('classes', [
            'user_id' => $this->teacher->id,
            'class_name' => 'Database Systems',
            'class_code' => 'CS-302',
            'year' => 3,
            'block' => 'B',
            'semester' => 'Second',
            'academic_year' => '2025-2026',
            'capacity' => 35,
        ]);
    }

    public function test_teacher_cannot_create_class_with_duplicate_code_for_same_teacher(): void
    {
        SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $response = $this->actingAs($this->teacher)
            ->post('/classes', [
                'class_name' => 'Another Web Development Class',
                'class_code' => 'IT-301', // duplicate
                'year' => '3',
                'block' => 'B',
                'semester' => 'First',
                'academic_year' => '2025-2026',
                'capacity' => '40',
            ]);

        $response->assertSessionHasErrors(['class_code']);
    }

    public function test_different_teachers_can_have_same_class_code(): void
    {
        SchoolClass::create([
            'user_id' => $this->otherTeacher->id,
            'class_name' => 'Other Teacher Class',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $response = $this->actingAs($this->teacher)
            ->post('/classes', [
                'class_name' => 'My Web Development Class',
                'class_code' => 'IT-301', // same code, but different teacher
                'year' => '3',
                'block' => 'A',
                'semester' => 'First',
                'academic_year' => '2025-2026',
                'capacity' => '40',
            ]);

        $response->assertRedirect('/classes');
        $response->assertSessionHas('success');
    }

    public function test_teacher_can_update_class(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $response = $this->actingAs($this->teacher)
            ->put("/classes/{$class->id}", [
                'class_name' => 'Advanced Web Dev',
                'class_code' => 'IT-301-ADV',
                'year' => '4',
                'block' => 'C',
                'semester' => 'Second',
                'academic_year' => '2026-2027',
                'capacity' => '45',
            ]);

        $response->assertRedirect('/classes');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('classes', [
            'id' => $class->id,
            'class_name' => 'Advanced Web Dev',
            'class_code' => 'IT-301-ADV',
            'year' => 4,
            'block' => 'C',
            'semester' => 'Second',
            'academic_year' => '2026-2027',
            'capacity' => 45,
        ]);
    }

    public function test_teacher_cannot_update_other_teachers_class(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->otherTeacher->id,
            'class_name' => 'Algorithms',
            'class_code' => 'CS-201',
            'year' => 2,
            'block' => 'B',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 30,
        ]);

        $response = $this->actingAs($this->teacher)
            ->put("/classes/{$class->id}", [
                'class_name' => 'Hacked Algorithms',
                'class_code' => 'CS-201',
                'year' => '2',
                'block' => 'B',
                'semester' => 'First',
                'academic_year' => '2025-2026',
                'capacity' => '30',
            ]);

        $response->assertStatus(403);
    }

    public function test_teacher_can_delete_class(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $response = $this->actingAs($this->teacher)
            ->delete("/classes/{$class->id}");

        $response->assertRedirect('/classes');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('classes', ['id' => $class->id]);
    }

    public function test_teacher_cannot_delete_other_teachers_class(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->otherTeacher->id,
            'class_name' => 'Algorithms',
            'class_code' => 'CS-201',
            'year' => 2,
            'block' => 'B',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 30,
        ]);

        $response = $this->actingAs($this->teacher)
            ->delete("/classes/{$class->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('classes', ['id' => $class->id]);
    }

    public function test_teacher_can_enroll_and_unenroll_students(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student1 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'section' => 'BSIT-3A',
        ]);

        $student2 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-002',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'section' => 'BSIT-3A',
        ]);

        // Enroll students
        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/enroll", [
                'student_ids' => [$student1->id, $student2->id],
            ]);

        $response->assertRedirect("/classes/{$class->id}?tab=roster");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('class_student', [
            'class_id' => $class->id,
            'student_id' => $student1->id,
        ]);
        $this->assertDatabaseHas('class_student', [
            'class_id' => $class->id,
            'student_id' => $student2->id,
        ]);

        // Unenroll student
        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/unenroll/{$student1->id}");

        $response->assertRedirect("/classes/{$class->id}?tab=roster");
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('class_student', [
            'class_id' => $class->id,
            'student_id' => $student1->id,
        ]);
        $this->assertDatabaseHas('class_student', [
            'class_id' => $class->id,
            'student_id' => $student2->id,
        ]);
    }

    public function test_teacher_cannot_enroll_other_teachers_student(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $otherStudent = Student::create([
            'user_id' => $this->otherTeacher->id,
            'student_id_number' => 'STUD-999',
            'first_name' => 'Foreign',
            'last_name' => 'Student',
            'section' => 'BSIT-3A',
        ]);

        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/enroll", [
                'student_ids' => [$otherStudent->id],
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('class_student', [
            'class_id' => $class->id,
            'student_id' => $otherStudent->id,
        ]);
    }

    public function test_teacher_can_save_class_attendance(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'section' => 'BSIT-3A',
        ]);

        // Enroll the student
        $class->students()->attach($student->id);

        $date = today()->toDateString();

        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $date,
                'attendance' => [
                    $student->id => [
                        'status' => 'late',
                        'remarks' => 'Traffic jam',
                    ]
                ]
            ]);

        $response->assertRedirect("/classes/{$class->id}?tab=attendance&date={$date}");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'user_id' => $this->teacher->id,
            'date' => $date,
            'status' => 'late',
            'remarks' => 'Traffic jam',
        ]);
    }

    public function test_teacher_can_save_class_attendance_with_time_in(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'section' => 'BSIT-3A',
        ]);

        $class->students()->attach($student->id);

        $date = today()->toDateString();

        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $date,
                'attendance' => [
                    $student->id => [
                        'status' => 'present',
                        'time_in' => '02:15 PM',
                    ]
                ]
            ]);

        $response->assertRedirect("/classes/{$class->id}?tab=attendance&date={$date}");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'user_id' => $this->teacher->id,
            'date' => $date,
            'status' => 'present',
            'time_in' => '14:15:00',
        ]);
    }

    public function test_teacher_can_reset_class_attendance_by_clearing_status(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'section' => 'BSIT-3A',
        ]);

        // Enroll the student
        $class->students()->attach($student->id);

        $date = today()->toDateString();

        // 1. Save attendance first
        $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $date,
                'attendance' => [
                    $student->id => [
                        'status' => 'present',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'date' => $date,
            'status' => 'present',
        ]);

        // 2. Reset (clear status) and save
        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $date,
                'attendance' => [
                    $student->id => [
                        'status' => '', // Cleared
                        'remarks' => '',
                    ]
                ]
            ]);

        $response->assertRedirect("/classes/{$class->id}?tab=attendance&date={$date}");
        $response->assertSessionHas('success');

        // 3. Assert database record is deleted
        $this->assertDatabaseMissing('attendances', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'date' => $date,
        ]);
    }

    public function test_teacher_can_reset_class_attendance_by_submitting_empty_request(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'section' => 'BSIT-3A',
        ]);

        $class->students()->attach($student->id);

        $date = today()->toDateString();

        // 1. Save attendance first
        $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $date,
                'attendance' => [
                    $student->id => [
                        'status' => 'present',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'date' => $date,
            'status' => 'present',
        ]);

        // 2. Submit request with NO attendance field
        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $date,
            ]);

        $response->assertRedirect("/classes/{$class->id}?tab=attendance&date={$date}");
        $response->assertSessionHas('success');

        // 3. Assert database record is deleted
        $this->assertDatabaseMissing('attendances', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'date' => $date,
        ]);
    }

    public function test_teacher_cannot_save_class_attendance_on_past_or_future_dates(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'section' => 'BSIT-3A',
        ]);

        // Enroll the student
        $class->students()->attach($student->id);

        // Past date
        $pastDate = today()->subDay()->toDateString();
        $responsePast = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $pastDate,
                'attendance' => [
                    $student->id => [
                        'status' => 'present'
                    ]
                ]
            ]);
        $responsePast->assertSessionHasErrors(['date']);

        // Future date
        $futureDate = today()->addDay()->toDateString();
        $responseFuture = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/attendance", [
                'date' => $futureDate,
                'attendance' => [
                    $student->id => [
                        'status' => 'present'
                    ]
                ]
            ]);
        $responseFuture->assertSessionHasErrors(['date']);
    }


    public function test_teacher_can_search_classes(): void
    {
        SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Unique Class Name One',
            'class_code' => 'CODE-111',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Another Class Name Two',
            'class_code' => 'CODE-222',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $response = $this->actingAs($this->teacher)
            ->get('/classes?search=Unique');

        $response->assertOk();
        $response->assertSee('Unique Class Name One');
        $response->assertDontSee('Another Class Name Two');

        $responseCode = $this->actingAs($this->teacher)
            ->get('/classes?search=CODE-222');

        $responseCode->assertOk();
        $responseCode->assertDontSee('Unique Class Name One');
        $responseCode->assertSee('Another Class Name Two');
    }

    public function test_teacher_can_search_class_roster(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student1 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'Alice',
            'last_name' => 'Wonderland',
            'section' => 'BSIT-3A',
        ]);

        $student2 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-002',
            'first_name' => 'Bob',
            'last_name' => 'Builder',
            'section' => 'BSIT-3A',
        ]);

        $class->students()->attach([$student1->id, $student2->id]);

        $response = $this->actingAs($this->teacher)
            ->get("/classes/{$class->id}?tab=roster&search=Alice");

        $response->assertOk();
        $response->assertSee('Alice');
        $response->assertDontSee('Bob');

        $responseId = $this->actingAs($this->teacher)
            ->get("/classes/{$class->id}?tab=roster&search=STUD-002");

        $responseId->assertOk();
        $responseId->assertDontSee('Alice');
        $responseId->assertSee('Bob');
    }

    public function test_teacher_can_search_class_attendance_sheet(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student1 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'Alice',
            'last_name' => 'Wonderland',
            'section' => 'BSIT-3A',
        ]);

        $student2 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-002',
            'first_name' => 'Bob',
            'last_name' => 'Builder',
            'section' => 'BSIT-3A',
        ]);

        $class->students()->attach([$student1->id, $student2->id]);

        $response = $this->actingAs($this->teacher)
            ->get("/classes/{$class->id}?tab=attendance&search=Alice");

        $response->assertOk();
        $response->assertSee('Alice');
        $response->assertDontSee('Bob');
    }

    public function test_teacher_can_bulk_unenroll_students(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $student1 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'section' => 'BSIT-3A',
        ]);

        $student2 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-002',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'section' => 'BSIT-3A',
        ]);

        $student3 = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => 'STUD-003',
            'first_name' => 'Jim',
            'last_name' => 'Beam',
            'section' => 'BSIT-3A',
        ]);

        // Enroll students
        $class->students()->attach([$student1->id, $student2->id, $student3->id]);

        // Bulk Unenroll student1 and student2
        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/bulk-unenroll", [
                'student_ids' => [$student1->id, $student2->id],
            ]);

        $response->assertRedirect("/classes/{$class->id}?tab=roster");
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('class_student', [
            'class_id' => $class->id,
            'student_id' => $student1->id,
        ]);
        $this->assertDatabaseMissing('class_student', [
            'class_id' => $class->id,
            'student_id' => $student2->id,
        ]);
        $this->assertDatabaseHas('class_student', [
            'class_id' => $class->id,
            'student_id' => $student3->id,
        ]);
    }

    public function test_teacher_cannot_bulk_unenroll_other_teachers_student(): void
    {
        $class = SchoolClass::create([
            'user_id' => $this->teacher->id,
            'class_name' => 'Web Development',
            'class_code' => 'IT-301',
            'year' => 3,
            'block' => 'A',
            'semester' => 'First',
            'academic_year' => '2025-2026',
            'capacity' => 40,
        ]);

        $otherStudent = Student::create([
            'user_id' => $this->otherTeacher->id,
            'student_id_number' => 'STUD-999',
            'first_name' => 'Foreign',
            'last_name' => 'Student',
            'section' => 'BSIT-3A',
        ]);

        $class->students()->attach($otherStudent->id);

        $response = $this->actingAs($this->teacher)
            ->post("/classes/{$class->id}/bulk-unenroll", [
                'student_ids' => [$otherStudent->id],
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('class_student', [
            'class_id' => $class->id,
            'student_id' => $otherStudent->id,
        ]);
    }
}
