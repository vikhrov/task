@extends('layouts.app')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Positions') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="float-right">
                        <a href="{{ route('positions.create') }}" class="btn btn-primary">Add Position</a>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="content">
        <div class="container-fluid">
            <table id="positions" class="display" style="width:100%">
                <thead>
                <tr>
                    <th style="width: 80%;">Name</th>
                    <th style="width: 10%;">Last update</th>
                    <th style="width: 10%;">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($positions as $position)
                    <tr>
                        <td>{{ $position->name }}</td>
                        <td>{{ $position->updated_at }}</td>
                        <td>
                            <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-sm btn-light">
                                    <span class="d-flex align-items-center">
                                        <i class="far fa-edit mr-1"></i>
                                    </span>
                            </a>
                            <form action="{{ route('positions.destroy', $position->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light" onclick="return confirm('Вы уверены?')">
                                        <span class="d-flex align-items-center">
                                            <i class="far fa-trash-alt mr-1"></i>
                                        </span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
