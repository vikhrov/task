@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Add Position') }}</h1>
                </div>
            </div>
        </div>
    </div>



    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('positions.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="admin_created_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="admin_updated_id" value="{{ Auth::id() }}">

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" value="{{ old('name', $position->name) }}" class="form-control">
                            <small id="charCount" style="text-align: end" class="form-text text-muted"></small>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create Position</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
