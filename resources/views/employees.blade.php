@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Employees') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="float-right">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">

            <table id="employees" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Date of employment</th>
                    <th>Phone number</th>
                    <th>Email</th>
                    <th>Salary</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>@if($employee->photo)
                                <img src="{{ asset("storage/photos/{$employee->photo}") }}" alt="{{ $employee->name }}" width="50">
                            @else
                                No Photo
                            @endif
                        </td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->position->name }}</td>
                        <td>{{ $employee->date_of_employment }}</td>
                        <td>{{ $employee->phone_number }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->salary }}</td>
                        <td>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-light">
                                    <span class="d-flex align-items-center">
                                        <i class="far fa-edit mr-1"></i> <!-- Иконка карандаша -->
                                    </span>
                            </a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light" onclick="return confirm('Вы уверены?')">
                                        <span class="d-flex align-items-center">
                                            <i class="far fa-trash-alt mr-1"></i> <!-- Иконка корзины -->
                                        </span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>


        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->


@endsection
