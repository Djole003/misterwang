@extends('admin.layouts.app')
@include('partials.header')
@section('content')
<div class="product-edit-container">
    <h1>Izmeni proizvod</h1>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="name" class="product-edit-label">Naziv proizvoda</label>
        <input type="text" name="name" id="name" class="product-edit-input @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required maxlength="255">
        @error('name')
            <div class="product-edit-invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="description" class="product-edit-label mt-3">Opis</label>
        <textarea name="description" id="description" class="product-edit-textarea @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
        @error('description')
            <div class="product-edit-invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="price" class="product-edit-label mt-3">Cena (RSD)</label>
        <input type="number" name="price" id="price" class="product-edit-input @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
        @error('price')
            <div class="product-edit-invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="category" class="product-edit-label mt-3">Kategorija</label>
        <input type="text" name="category" id="category" class="product-edit-input @error('category') is-invalid @enderror" value="{{ old('category', $product->category) }}" maxlength="100" required>
        @error('category')
            <div class="product-edit-invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="image" class="product-edit-label mt-3">Promeni sliku proizvoda (opciono)</label>
        @if($product->image_path)
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="product-edit-image">
        @endif
        <input type="file" name="image" id="image" class="product-edit-file @error('image') is-invalid @enderror" accept="image/*">
        @error('image')
            <div class="product-edit-invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="mt-4">
            <button type="submit" class="product-edit-btn-submit">Sačuvaj izmene</button>
            <a href="{{ route('admin.products.index') }}" class="product-edit-btn-back">Nazad</a>
        </div>
    </form>
</div>
@endsection