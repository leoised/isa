<?php


namespace Database\Factories;


use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;


class CourseFactory extends Factory
{
protected $model = Course::class;


public function definition()
{
return [
'title' => ucfirst($this->faker->words(3, true)),
'description' => $this->faker->sentence(),
'credits' => $this->faker->numberBetween(2,5),
];
}
}