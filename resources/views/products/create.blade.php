@extends('layouts.app')

@section('content')
<h2>Novi proizvod</h2>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Naziv</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Opis</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label>Cena</label>
        <input type="number" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Kategorija</label>
        <input type="text" name="category" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Slika</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button class="btn btn-success">Sačuvaj</button>
</form>
@endsection
