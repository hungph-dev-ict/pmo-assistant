@extends('layouts.app')

@section('inline_css')
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('page_title')
    Components
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Components</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Components</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Name</th>
                                        <th style="width: 60%;">Memo</th>
                                        <th style="width: 10%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($components as $component)
                                        <tr class="{{ $component->deleted_at ? 'table-secondary' : '' }}">
                                            <td>{{ $component->name }}</td>
                                            <td>{{ $component->memo }}</td>
                                            <td>
                                                @if ($component->deleted_at)
                                                    <form method="POST"
                                                        action="{{ route('pm.components.restore', ['project_id' => $project_id, 'id' => $component->id]) }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm">Restore</button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('pm.components.delete', ['project_id' => $project_id, 'id' => $component->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create New Components</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pm.components', $project_id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="memo">Memo</label>
                                    <textarea id="memo" name="memo" class="form-control" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
