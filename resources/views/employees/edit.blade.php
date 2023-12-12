@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Edit Employee') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="photo">Фотографія:</label>

                            <!-- Показываем текущее фото (если есть) -->
                            @if ($employee->photo)
                                <img src="{{ asset("storage/photos/{$employee->photo}") }}" alt="Employee Photo" class="img-thumbnail mb-2" style="max-width: 300px; max-height: 300px;">
                            @else
                                <!-- Рисуем квадрат, если фото не загружено -->
                                <div class="img-thumbnail mb-2" style="width: 100px; height: 100px; border: 1px solid #ddd; text-align: center;">JPG 300x300px 80% quality</div>
                            @endif

                            <!-- Поле для загрузки нового фото -->
                            <input type="file" class="form-control-file" id="photo" name="photo">

                            <small class="form-text text-muted">File format jpg, png up to 5Mb,the minimum size of 300x300px</small>

                        </div>
                        @if ($errors->has('photo'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('photo') }}</strong>
                            </span>
                        @endif

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small id="charCount" style="text-align: end" class="form-text text-muted"></small>
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone:</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $employee->phone_number) }}" class="form-control">
                            @error('phone_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted" style="text-align: end">Required format +380XXXXXXXXX</small>

                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" value="{{ $employee->email }}" class="form-control">
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="position_id">Position:</label>
                            <select name="position_id" class="form-control autocomplete-select">
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('position_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="salary">Salary:</label>
                            <input type="text" name="salary" value="{{ $employee->salary }}" class="form-control">
                            @error('salary')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Manager:</label>
                            <select class="select-manager form-control" name="parent_id">
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ $employee->parent_id == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_of_employment">Date of employment:</label>
                            <input type="date" name="date_of_employment" value="{{ $employee->date_of_employment }}" class="form-control">
                            @error('date_of_employment')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Created At: {{ $employee->created_at }}</p>
                                    <p>Updated At: {{ $employee->updated_at }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Admin Created ID: {{ $employee->admin_created_id }}</p>
                                    <p>Admin Updated ID: {{ $employee->admin_updated_id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                        <a href="{{ route('employees.cancelUpdate') }}" class="btn btn-secondary">Cancel</a>

                    </form>
                </div>
            </div>
        </div>
    </div>




@endsection
