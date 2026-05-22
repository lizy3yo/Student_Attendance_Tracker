<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
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

    public function test_teacher_can_view_students_index(): void
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
            'student_id_number' => '202400123',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'section' => 'BSIT-A',
            'email' => '202400123@gordoncollege.edu.ph',
        ]);

        $class->students()->attach($student->id);

        $response = $this->actingAs($this->teacher)->get('/students');

        $response->assertOk();
        $response->assertSee('Juan Dela Cruz');
        $response->assertSee('202400123');
        $response->assertSee('BSIT-A');
        $response->assertSee('IT-301');
    }

    public function test_teacher_can_add_student_with_concatenation(): void
    {
        $response = $this->actingAs($this->teacher)
            ->post('/students', [
                'student_id_number' => '202400123', // Raw 9 digits
                'first_name'        => 'Juan',
                'middle_name'       => 'Macalincag',
                'last_name'         => 'Dela Cruz',
                'suffix'            => 'Jr',
            ]);

        $response->assertRedirect('/students');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'user_id'           => $this->teacher->id,
            'student_id_number' => '202400123', // Numeric only
            'first_name'        => 'Juan Macalincag', // Combined with middle name
            'last_name'         => 'Dela Cruz Jr', // Combined with suffix
            'section'           => 'N/A', // Defaults to N/A
            'email'             => '202400123@gordoncollege.edu.ph', // Auto-generated
        ]);
    }

    public function test_teacher_can_update_student(): void
    {
        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => '202400123',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'section' => 'BSIT-A',
        ]);

        $response = $this->actingAs($this->teacher)
            ->put("/students/{$student->id}", [
                'student_id_number' => '202400999', // Raw update
                'first_name'        => 'Juanito',
                'last_name'         => 'Dela Cruz',
                'section'           => 'BSIT-2B',
                'email'             => '202400999@gordoncollege.edu.ph',
            ]);

        $response->assertRedirect('/students');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'id'                => $student->id,
            'student_id_number' => '202400999', // Numeric update
            'first_name'        => 'Juanito',
            'last_name'         => 'Dela Cruz',
            'section'           => 'BSIT-2B',
            'email'             => '202400999@gordoncollege.edu.ph',
        ]);
    }

    public function test_teacher_can_update_student_with_concatenation(): void
    {
        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => '202400123',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'section' => 'BSIT-A',
        ]);

        $response = $this->actingAs($this->teacher)
            ->put("/students/{$student->id}", [
                'student_id_number' => '202400999',
                'first_name'        => 'Juanito',
                'middle_name'       => 'Macalincag',
                'last_name'         => 'Dela Cruz',
                'suffix'            => 'Jr',
                'email'             => '202400999@gordoncollege.edu.ph',
            ]);

        $response->assertRedirect('/students');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'id'                => $student->id,
            'student_id_number' => '202400999',
            'first_name'        => 'Juanito Macalincag',
            'last_name'         => 'Dela Cruz Jr',
            'email'             => '202400999@gordoncollege.edu.ph',
        ]);
    }

    public function test_teacher_can_update_student_preserving_section(): void
    {
        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => '202400123',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'section' => 'BSIT-A',
        ]);

        $response = $this->actingAs($this->teacher)
            ->put("/students/{$student->id}", [
                'student_id_number' => '202400999',
                'first_name'        => 'Juanito',
                'last_name'         => 'Dela Cruz',
            ]);

        $response->assertRedirect('/students');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'id'                => $student->id,
            'student_id_number' => '202400999',
            'first_name'        => 'Juanito',
            'last_name'         => 'Dela Cruz',
            'section'           => 'BSIT-A', // Remains unchanged
        ]);
    }

    public function test_teacher_cannot_update_other_teachers_student(): void
    {
        $student = Student::create([
            'user_id' => $this->otherTeacher->id,
            'student_id_number' => '202400123',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'section' => 'BSIT-A',
        ]);

        $response = $this->actingAs($this->teacher)
            ->put("/students/{$student->id}", [
                'student_id_number' => '202400999',
                'first_name'        => 'Hacked',
                'last_name'         => 'Student',
                'section'           => 'BSIT-3A',
            ]);

        $response->assertStatus(403);
    }

    public function test_teacher_can_delete_student(): void
    {
        $student = Student::create([
            'user_id' => $this->teacher->id,
            'student_id_number' => '202400123',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'section' => 'BSIT-A',
        ]);

        $response = $this->actingAs($this->teacher)
            ->delete("/students/{$student->id}");

        $response->assertRedirect('/students');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }

    public function test_teacher_cannot_delete_other_teachers_student(): void
    {
        $student = Student::create([
            'user_id' => $this->otherTeacher->id,
            'student_id_number' => '202400123',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'section' => 'BSIT-A',
        ]);

        $response = $this->actingAs($this->teacher)
            ->delete("/students/{$student->id}");

        $response->assertStatus(403);
    }
}
