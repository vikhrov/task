<?php

namespace App\Datatables;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use DataTables;
use Exception;
use Yajra\DataTables\DataTableAbstract;

class EmployeeDataTable
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function dataTable(): DataTableAbstract
    {
        return DataTables::of($this->employeeRepository->prepareDataQuery())
            ->addColumn('position', fn (Employee $employee) => $employee->position->name)
            ->addColumn(
                'date_of_employment',
                fn (Employee $employee) => $employee->date_of_employment->format("Y-m-d")
            )
            ->addColumn('photo', function (Employee $employee) {
                $photoPath = $employee->photo !== '0'
                    ? "storage/photos/{$employee->photo}"
                    : 'storage/photos/avatar-stub.png';

                return view('employees.partials.photo', compact('photoPath'));
            })
            ->addColumn('actions', fn (Employee $employee) => view('employees.partials.actions', [
                'employeeId' => $employee->id,
            ]))
            ->setRowId(fn (Employee $employee) => $employee->id);
    }
}
