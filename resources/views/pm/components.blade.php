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
                                        <tr id="row-{{ $component->id }}"
                                            class="{{ $component->deleted_at ? 'table-secondary' : '' }}">
                                            <td>{{ $component->name }}</td>
                                            <td>{{ $component->memo }}</td>
                                            <td>
                                                @if ($component->deleted_at)
                                                    <form method="POST"
                                                        action="{{ route('pm.components.restore', ['project_id' => $project_id, 'id' => $component->id]) }}"
                                                        class="restore-form" data-id="{{ $component->id }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm">Restore</button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('pm.components.delete', ['project_id' => $project_id, 'id' => $component->id]) }}"
                                                        class="delete-form" data-id="{{ $component->id }}">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.addEventListener("submit", function(event) {
            if (event.target.classList.contains('delete-form')) {
                event.preventDefault();
                let form = event.target;
                let componentId = form.getAttribute('data-id');
                let projectId = "{{ request()->route('project_id') }}";
                let row = document.getElementById('row-' + componentId);
                console.log("DELETE request to:", form.action);
                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: "DELETE"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            row.classList.add('table-secondary');
                            row.querySelector('td:last-child').innerHTML = `
                        <form method="POST" action="/pm/${projectId}/components/${componentId}/restore" class="restore-form" data-id="${componentId}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                        </form>
                    `;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
