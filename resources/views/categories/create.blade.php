@extends('layouts.main')

@section('content')

    <div class="card shadow rounded">
        <div class="card-header">
            <h3>Kategoriya qo'shish</h3>
        </div>

        <div class="card-body">

            <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="name" class="">Nomi</label>
                            <input name="name" id="name" type="text" class="form-control">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="emoji" class="">Emoji</label>
                            <input name="emoji" id="emoji" type="text" class="form-control"></div>
                        @error('emoji')
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


                </div>
                <input type="submit" class="mt-2 btn btn-primary" value="Qo'shish">
            </form>


        </div>

    </div>


@endsection
