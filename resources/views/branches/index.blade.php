@extends('layouts.main')

@section('content')


    <div class="card mb-4">
        <div class="card-body">

            <div class="container-fluid">
                <div class="row justify-content-between">
                    <div class="h3">Filiallar</div>
                    <div>
                        <a href="{{ route('branch.create') }}" class="btn btn-success"><b>+</b> Qo'shish</a>
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
                    <td>Yaratilgan sana</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($branches as $branch)
                    <tr>
                        <td>{{ $branch->id }}</td>
                        <td>{{ $branch->title }}</td>
                        <td>{{ $branch->created_at }}</td>
                        <td width="250px">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-primary">O'zgaritirish</a>
                                </div>
                                <div>
                                    <form action="{{ route('branch.destroy', $branch->id) }}" method="post">
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


            {{ $branches->links() }}

        </div>

    </div>


@endsection
