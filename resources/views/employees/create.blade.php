@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Add Employee') }}</h1>
                </div>
            </div>
        </div>
    </div>



    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="photo">Фотографія:</label>
                            <input type="file" class="form-control-file" id="photo" name="photo">
                            @error('photo')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">File format jpg, png up to 5Mb,the minimum size of 300x300px</small>

                        </div>

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control">
                            @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <small id="charCount" style="text-align: end" class="form-text text-muted"></small>
                        </div>


                        <div class="form-group">
                            <label for="position_id">Position:</label>
                            <select name="position_id" value="{{ old('position_id', $employee->position_id) }}" class="form-control">
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="date_of_employment">Date of employment:</label>
                            <input type="date" name="date_of_employment" value="{{ old('date_of_employment', $employee->date_of_employment) }}" class="form-control">
                            @error('date_of_employment')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone number:</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $employee->phone_number) }}" class="form-control">
                            @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted" style="text-align: end">Required format +380XXXXXXXXX</small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control">
                            @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="salary">Salary:</label>
                            <input type="text" name="salary" value="{{ old('salary', $employee->salary) }}" class="form-control">
                            @error('salary')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Manager:</label>
                            <select class="select-manager form-control" name="parent_id">
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add Employee</button>
                        </div>
                        <a href="{{ route('employees.cancelUpdate') }}" class="btn btn-secondary">Cancel</a>


                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select-manager').select2();
        });
    </script>

@endsection
