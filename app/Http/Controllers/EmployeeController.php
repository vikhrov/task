<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Exceptions\PostTooLargeException;
use App\Models\Position;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;
use App\Validators\EmployeeValidator;


class EmployeeController extends Controller
{
    public function __construct(ImageService $imageService)
    {
        $this->middleware('auth');
        $this->imageService = $imageService;
    }

    public function index() {

//        $employees = Employee::paginate(10);
        $employees = Employee::all();
        return view('employees', compact('employees'));
    }




    public function create()
    {
        $employee = new Employee();
        $managers = Employee::where('level', '<>', 5)->get(['id', 'name']);
        $positions = Position::all();

        return view('employees.create', compact('managers', 'positions', 'employee'));
    }

    public function store(Request $request, ImageService $imageService)
    {
        try {
            $validatedData = $request->all();

            $validator = Validator::make(
                $validatedData,
                EmployeeValidator::rules(),
                EmployeeValidator::messages()
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->messages())->withInput();
            }

            // Создание нового сотрудника
            $employee = Employee::create($validatedData);

            // Обработка фотографии
            $this->imageService->processImage($request, $employee);

            // Перенаправление после успешного добавления
            return redirect()->route('employees.index')->with('success', 'Сотрудник успешно добавлен');
        } catch (ValidationException $e) {
            // Обработка ошибок валидации
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function getManagers(Request $request)
    {
        $term = $request->input('term');

        $managers = Employee::where('level', '<>', 5)
            ->where('name', 'LIKE', '%' . $term . '%')
            ->take(100)
            ->get(['id', 'name']);

        $formattedManagers = [];
        foreach ($managers as $manager) {
            $formattedManagers[] = ['id' => $manager->id, 'text' => $manager->name];
        }

        return response()->json($formattedManagers);
    }

    // Fetch records
    public function getEmployees(Request $request){

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Employee::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Employee::leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->where(function ($query) use ($searchValue) {
                $query->where('employees.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.date_of_employment', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('positions.name', 'like', '%' . $searchValue . '%'); // Условие для поиска по должности
            })
            ->count();
        // Fetch records
//        $records = Employee::orderBy($columnName,$columnSortOrder)
//            ->where('employees.name', 'like', '%' .$searchValue . '%')
//                ->orWhere('employees.date_of_employment', 'like', '%' . $searchValue . '%')
//                ->orWhere('employees.email', 'like', '%' . $searchValue . '%')
//
//            ->select('employees.*')
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
        $records = Employee::with('position')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->select('employees.*', 'positions.name as position_name') // Добавление алиаса для positions.name
            ->where(function ($query) use ($searchValue) {
                $query->where('employees.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.date_of_employment', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('positions.name', 'like', '%' . $searchValue . '%');
            })
            ->orderBy($columnName == 'position' ? 'position_name' : $columnName, $columnSortOrder) // Использование алиаса для сортировки
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            if ($record->photo) {
                $photo = '<img src="' . asset("storage/photos/{$record->photo}") . '" style="max-width: 50px; max-height: 50px; border-radius: 50%; overflow: hidden;">';
            } else {
                $photo = '<div style="width: 50px; height: 50px; background-color: #ccc; border-radius: 50%;"></div>';
            }
            //            $photo = '<img src="' . $record->photo . '" alt="Photo" width="50" height="50">';
            $name = $record->name;
            $position = $record->position ? $record->position->name : '';
            $date_of_employment = $record->date_of_employment;
            $phone_number = $record->phone_number;
            $email = $record->email;
            $salary = $record->salary;



            $data_arr[] = array(
                "id" => $id,
                "photo" => $photo,
                "name" => $name,
                "position" => $position,
                "date_of_employment" => $date_of_employment,
                "phone_number" => $phone_number,
                "email" => $email,
                "salary" => $salary,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function edit(Employee $employee)
    {

        $managers = Employee::where('id', '<>', $employee->id)
            ->where('level', '<', $employee->level)
            ->get();
        $positions = Position::all();

        return view('employees.edit', ['employee' => $employee, 'managers' => $managers, 'positions' => $positions]);
    }

    public function update(Request $request, Employee $employee, ImageService $imageService)
    {
        try {
            $validatedData = $request->all();

            $validator = Validator::make($validatedData, EmployeeValidator::rules($employee->id), EmployeeValidator::messages());


            // Проверка наличия файла перед применением валидации изображения
            $this->imageService->processImage($request, $employee);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->messages())->withInput();
            }

            // Обновление данных существующего сотрудника
            $employee->update($request->except('photo'));

            Log::info("Employee updated successfully: ID {$employee->id}");

            // Перенаправление после успешного обновления
            return redirect()->route('employees.index')->with('success', 'Сотрудник успешно обновлен');
        } catch (ValidationException $e) {
            // Обработка ошибок валидации
            Log::error("Validation error during employee update: " . $e->getMessage());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Обработка других ошибок
            Log::error("Error updating employee: " . $e->getMessage());
            return redirect()->back()->with('error', 'Произошла ошибка при обновлении сотрудника.')->withInput();
        }
    }





    public function cancelUpdate() {
        return redirect()->route('employees.index')->with('status', 'Изменения отменены');
    }

    public function destroy($id)
    {
        try {

            // Находим сотрудника, которого мы собираемся удалить
            $employeeToDelete = Employee::findOrFail($id);

            // Находим всех подчиненных данного сотрудника
            $subordinates = Employee::where('parent_id', $employeeToDelete->id)->get();

            // Находим нового начальника того же уровня, исключая удаляемого сотрудника
            $newManager = Employee::where('level', $employeeToDelete->level)
                ->where('id', '!=', $employeeToDelete->id)
                ->first();

            // Если не удалось найти нового начальника того же уровня
            if (!$newManager) {
                return redirect()->route('employees.index')->with('error', 'Не удалось найти нового начальника для подчиненных.');
            }

            // Обновляем начальника для каждого подчиненного, исключая удаляемого сотрудника
            foreach ($subordinates as $subordinate) {
                $subordinate->parent_id = $newManager->id;
                $subordinate->save();
                // Выводим в логи новый parent_id после обновления
            }

            // Удаляем сотрудника
            $employeeToDelete->delete();

            return redirect()->route('employees.index')->with('success', 'Сотрудник успешно удален');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Произошла ошибка при удалении сотрудника.');
        }
    }
}


