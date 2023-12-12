@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Edit Position') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('positions.update', $position->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{ old('name', $position->name) }}" class="form-control">
                            <small id="charCount" style="text-align: end" class="form-text text-muted"></small>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Created At: {{ $position->created_at }}</p>
                                    <p>Updated At: {{ $position->updated_at }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Admin Created ID: {{ $position->admin_created_id }}</p>
                                    <p>Admin Updated ID: {{ $position->admin_updated_id }}</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Обновить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
