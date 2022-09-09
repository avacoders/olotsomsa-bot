@extends('layouts.main')

@section('content')

    <div class="card shadow rounded">
        <div class="card-header">
            <h3>Maxsulot qo'shish</h3>
        </div>

        <div class="card-body">

            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="name" class="">Nomi</label>
                            <input name="name" id="name" type="text" value="{{ old('name') }}" class="form-control">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="order" class="">Tartib</label>
                            <input name="order" id="order" type="number"  class="form-control">
                            @error('order')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="price" class="">Narxi</label>
                            <input name="price" id="price" type="number" value="{{ old('price') }}" class="form-control"></div>
                        @error('price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="unit" class="">O'lchov</label>
                            <input name="unit" id="unit" type="text" value="{{ old('unit') }}" class="form-control"></div>
                        @error('unit')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="image" class="">Rasm</label>
                            <input name="image" id="image" type="file" class="form-control"></div>
                        @error('image')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 p-4">
                        <div class="position-relative form-group">
                            <input name="status" id="status" type="checkbox" {{ old('status') ? 'checked': '' }}>
                            <label for="status" class="pl-3">Yangi </label>
                        </div>

                        @error('status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="category_id" class="">Kategoriya</label>
                            <select type="select" id="category_id" name="category_id" class="custom-select">
                                <option value="">Tanlang</option>
                                @foreach($categories as $category)
                                    <option {{ old('category_id') == $category->id ? 'selected' :'' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <input type="submit" class="mt-2 btn btn-primary" value="Qo'shish">
            </form>


        </div>

    </div>


@endsection
