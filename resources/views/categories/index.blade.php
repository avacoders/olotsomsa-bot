@extends('layouts.main')

@section('content')




    <div class="card mb-4">
        <div class="card-body">

            <div class="container-fluid">
                <div class="row justify-content-between">
                    <div class="h3">Kategoriyalar</div>
                    <div>
                        <a href="{{ route('category.create') }}" class="btn btn-success"><b>+</b> Qo'shish</a>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="card shadow rounded">

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Nomi</td>
                    <td>Rasm</td>
                    <td>Yaratilgan sana</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td><img width="100" src="{{ $category->image }}" alt=""></td>
                        <td>{{ $category->created_at }}</td>
                        <td width="250px">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary">O'zgaritirish</a>
                                </div>
                                <div>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" class="btn btn-danger" value="O'chirish">
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>


            {{ $categories->links() }}

        </div>

    </div>


@endsection
