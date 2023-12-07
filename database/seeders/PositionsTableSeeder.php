<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 250) as $index) {
            DB::table('positions')->insert([
                'name' => $faker->randomElement(['Менеджер', 'Програміст', 'Дизайнер', 'Аналітик', 'Тестувальник']),
            ]);
        }
    }
}
