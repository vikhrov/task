<?php


namespace App\Validators;

use Illuminate\Validation\Rule;

class EmployeeValidator
{
    public static function rules($employeeId = null)
    {
        return [
            'name' => 'required|string|min:2|max:256',
            'position_id' => 'required|string',
            'date_of_employment' => 'required|date',
            'phone_number' => 'required|regex:/^\+380\d{9}$/',
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($employeeId),
            ],            'salary' => 'required|numeric|between:0,500000',
            'parent_id' => 'nullable|exists:employees,id',
            'photo' => 'image|mimes:jpeg,png|max:5120|dimensions:min_width=300,min_height=300',
        ];
    }

    public static function messages()
    {
        return [
            'phone_number.*' => 'Invalid phone number, required format +380XXXXXXXXX',
            'parent_id.*' => 'There is no such person in the database',
        ];
    }
}
