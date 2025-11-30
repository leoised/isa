<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;


class DatabaseSeeder extends Seeder
{
public function run()
{
$students = Student::factory()->count(10)->create();
$courses = Course::factory()->count(6)->create();


// random enrollments
foreach ($students as $s) {
$attach = $courses->random(rand(1,3))->pluck('id')->toArray();
$s->courses()->attach($attach);
}
}
}