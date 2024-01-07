<?php

namespace App\Http\Controllers\Employee\Api;

use App\Datatables\EmployeeDataTable;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class GetEmployeeListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request, EmployeeDataTable $dataTable): JsonResponse
    {
        return $dataTable->dataTable()->toJson();
    }

}
