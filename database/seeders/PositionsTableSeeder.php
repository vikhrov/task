<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class PositionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $positions = [];

        foreach (range(1, 250) as $ignored) {
            $positions[] = [
                'name' => $faker->randomElement(['Менеджер', 'Програміст', 'Дизайнер', 'Аналітик', 'Тестувальник']),
                'created_at' => $created = $faker->dateTimeBetween(),
                'updated_at' => $faker->dateTimeBetween($created),
            ];
        }

        DB::table('positions')->insert($positions);
    }
}
