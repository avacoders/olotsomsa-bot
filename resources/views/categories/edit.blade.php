@extends('layouts.main')

@section('content')

    <div class="card shadow rounded">
        <div class="card-header">
            <h3>Kategoriya O'zgartirish</h3>
        </div>

        <div class="card-body">

            <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="name" class="">Nomi</label>
                            <input name="name" id="name" type="text" value="{{ $category->name }}" class="form-control">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="emoji" class="">Emoji</label>
                            <input name="emoji" id="emoji" type="text" value="{{ $category->emoji }}"
                                   class="form-control"></div>
                        @error('emoji')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="image" class="">Rasm</label>


                            <input name="image" id="image" type="file" class="form-control"></div>
                        <img src="{{ $category->image }}" width="100" alt="">

                        @error('image')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>
                <input type="submit" class="mt-2 btn btn-primary" value="O'zgartirish">
            </form>


        </div>

    </div>


@endsection
