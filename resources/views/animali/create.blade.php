@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Nuovo Animale</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('animali.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Specie</label>
                <select name="specie" class="form-control" required>
                    <option value="Cane">Cane</option>
                    <option value="Gatto">Gatto</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Razza</label>
                <input type="text" name="razza" class="form-control">
            </div>

            <div class="mb-3">
                <label>Data di Nascita</label>
                <input type="date" name="data_nascita" class="form-control">
            </div>

            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
            </div>

            <div class="mb-3">
                <label>Note</label>
                <textarea name="note" class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('animali.index') }}" class="btn btn-secondary me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success">Aggiungi Animale</button>
            </div>
        </form>
    </div>
</div>

@endsection

