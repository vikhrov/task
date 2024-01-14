<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class EmployeeRepository
{
    public function __construct(
        private readonly Employee $model
    ) {
    }

    public function prepareDataQuery(
        string $column = 'name',
        string $order = 'asc'
    ): Builder {
        $table = $this->model->getTable();

        return $this->model::with([
            'position:id,name',
        ])->select([
            "{$table}.id",
            "{$table}.name",
            "{$table}.position_id",
            "{$table}.date_of_employment",
            "{$table}.phone_number",
            "{$table}.email",
            "{$table}.salary",
            "{$table}.photo",
        ])->orderBy($column, $order);
    }

    public function setNewPositionsForOldPosition(Position $position, SupportCollection $positionIds): void
    {
        $this->model::wherePositionId($position->id)
            ->chunkById(1000, function (Collection $collection) use ($positionIds) {
            $collection->each(function (Employee $employee) use ($positionIds) {
                $employee->position_id = (int) $positionIds->random();
                $employee->save();
            });
        });
    }
}
