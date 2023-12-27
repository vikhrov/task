<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use RuntimeException;


class EmployeesTableSeeder extends Seeder
{
    public function run(): void
    {
        $positionIds = Position::pluck('id');

        if ($positionIds->isEmpty()) {
            throw new RuntimeException('You must seed the positions');
        }

        $adminIds = User::pluck('id');

        if ($adminIds->isEmpty()) {
            throw new RuntimeException('You must seed at least one admin');
        }

        foreach(range(5, 1) as $level) {
            $parentIds = $level !== 5
                ? Employee::where('level', '=', $level + 1)->pluck('id')
                : null;

            $this->chunkSeed($positionIds, $adminIds, $level, $parentIds);
        }
    }

    private function chunkSeed(
        Collection $positionIds,
        Collection $adminIds,
        int $level = 5,
        ?Collection $parentIds = null
    ): void {
        for ($i = 0; $i < 10000; $i += 200) {
            $employeesRaw = Employee::factory(200)
                ->forPositions($positionIds)
                ->forAdmins($adminIds)
                ->forParents($parentIds)
                ->forLevel($level)
                ->raw();
            Employee::insert($employeesRaw);
        }
    }
}


