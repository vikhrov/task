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


class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

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

    public function store(Request $request, Employee $employee)
    {
        try {
            $validatedData = $request->all();
            $messages = [
                'phone_number.*' => 'Invalid phone number, required format +380XXXXXXXXX',
            ];

            $validator = Validator::make($validatedData, [
                'name' => 'required|string|min:2|max:256',
                'position_id' => 'required|string',
                'date_of_employment' => 'required|date',
                'phone_number' => 'required|regex:/^\+380\d{9}$/',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'salary' => 'required|numeric|between:0,500000',
                'parent_id' => 'nullable|exists:employees,id',
                'photo' => 'image|mimes:jpeg,png|max:5120|dimensions:min_width=300,min_height=300',
            ], $messages);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator->messages())->withInput();
            }
//            dd($request->all());
//            $employee->save($request->all());


            // Создание нового сотрудника
            $employee = Employee::create($validatedData);

            // Обработка фотографии
            if ($request->hasFile('photo')) {
                $uploadedFile = $request->file('photo');

                // Генерация уникального имени для файла
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();

                // Сохранение файла на сервере
                $uploadedFile->storeAs('photos', $fileName, 'public');

                // Изменение размера фотографии и ее сохранение
                $image = Image::make(storage_path("app/public/photos/$fileName"))
                    ->fit(300, 300, function ($constraint) {
                        $constraint->upsize(); // Сохранение пропорций
                    })
                    ->orientate() // Автоматический поворот при необходимости
                    ->encode('jpg', 80); // Сохранение в формате jpg с качеством 80%

                Storage::put("public/photos/$fileName", $image);

                // Обновление имени файла в базе данных
                $employee->update(['photo' => $fileName]);
            }

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
            ->get(['id', 'name']);

        return response()->json($managers);
    }




    public function edit(Employee $employee)
    {

        $managers = Employee::where('id', '<>', $employee->id)
            ->where('level', '<', $employee->level)
            ->get();
        $positions = Position::all();

        return view('employees.edit', ['employee' => $employee, 'managers' => $managers, 'positions' => $positions]);
    }

    public function update(Request $request, Employee $employee)
    {
        try {
            $validatedData = $request->all();

            $messages = [
                'phone_number.*' => 'Invalid phone number, required format +380XXXXXXXXX',
            ];

            $validator = Validator::make($validatedData, [
                'name' => 'required|string|min:2|max:256',
                'position_id' => 'required|string',
                'date_of_employment' => 'required|date',
                'phone_number' => 'required|regex:/^\+380\d{9}$/',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'salary' => 'required|numeric|between:0,500000',
                'parent_id' => 'nullable|exists:employees,id',
                'photo' => 'image|mimes:jpeg,png|max:5120|dimensions:min_width=300,min_height=300',

            ], $messages);

            // Проверка наличия файла перед применением валидации изображения
            if ($request->hasFile('photo')) {
                $validator->validate();

                // Обработка фотографии
                $uploadedFile = $request->file('photo');
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();

                // Сохранение файла на сервере
                $uploadedFile->storeAs('photos', $fileName, 'public');

                // Изменение размера фотографии и ее сохранение
                $image = Image::make(storage_path("app/public/photos/$fileName"))
                    ->fit(300, 300, function ($constraint) {
                        $constraint->upsize(); // Сохранение пропорций
                    })
                    ->orientate() // Автоматический поворот при необходимости
                    ->encode('jpg', 80); // Сохранение в формате jpg с качеством 80%

                Storage::put("public/photos/$fileName", $image);

                // Обновление имени файла в базе данных
                $employee->update(['photo' => $fileName]);
            }

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->messages())->withInput();
            }

            // Обновление данных существующего сотрудника
            $employee->update($validatedData);

            // Перенаправление после успешного обновления
            return redirect()->route('employees.index')->with('success', 'Сотрудник успешно обновлен');
        } catch (ValidationException $e) {
            // Обработка ошибок валидации
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
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
                \Log::error('Не удалось найти нового начальника для подчиненных.');
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


