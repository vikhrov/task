<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Contracts\Database\Eloquent\Builder;

class EmployeeRepository
{
    public function __construct(
        private Employee $model
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
}
