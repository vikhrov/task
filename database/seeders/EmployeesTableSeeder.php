<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Получаем данные о должностях внутри функции run
        $positions = DB::table('positions')->pluck('id')->toArray();

        foreach (range(1, 50000) as $index) {
            $level = ceil($index / 10000); // Розподілити співробітників між 5 рівнями

            $employee = [
                'name' => $faker->name,
                'position_id' => $faker->randomElement($positions),
                'email' => $faker->unique()->email,
                'date_of_employment' => $faker->dateTimeThisDecade,
                'phone_number' => '+380' . $faker->numerify('#########'),
                'salary' => $faker->numberBetween(20000, 50000),
                'photo' => 0,
                'admin_created_id' => 1,
                'admin_updated_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'level' => $level,
            ];

            // Якщо рівень не перший, вибрати випадковий батьківський елемент з попереднього рівня
            if ($level > 1) {
                $parent = DB::table('employees')->where('level', $level - 1)->inRandomOrder()->first();
                $employee['parent_id'] = $parent->id;
            }

            DB::table('employees')->insert($employee);
        }
    }
}


