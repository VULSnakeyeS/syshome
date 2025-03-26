@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Gestión de Opciones de Dropdown</h6>
                        <a href="{{ route('dropdown-options.create') }}" class="btn btn-primary">Agregar Opción</a>
                    </div>
                    <div class="card-body px-4 pt-0 pb-4">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Dropdown</th>
                                    <th>Opción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dropdownOptions as $option)
                                    <tr>
                                        <td>{{ $option->dropdown_name }}</td>
                                        <td>{{ $option->option_value }}</td>
                                        <td>
                                            <a href="{{ route('dropdown-options.edit', $option->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('dropdown-options.destroy', $option->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection