@extends('layouts.main')

@section('content')


    <div class="card mb-4">
        <div class="card-body">

            <div class="container-fluid">
                <div class="row justify-content-between">
                    <div class="h3">Maxsulotlar</div>
                    <div>
                        <a href="{{ route('user.create') }}" class="btn btn-success"><b>+</b> Qo'shish</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">

            <div class="container-fluid">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <form action="" >
                            <div class="input-group mb-3">
                                <input type="search" class="form-control" value="{{ request('search')  }}" name="search" placeholder="Telefon raqam yoki ism" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Qidirish</button>
                                </div>
                            </div>
                        </form>
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
                    <td>Username</td>
                    <td>Telefon raqami</td>
                    <td>Yaratilgan sana</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->telegram_id }}</td>
                        <td>{{ $user->name }} @if($user->status) <div class="mb-2 mr-2 badge badge-pill badge-success">Yangi</div> @endif</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td width="250px">
                            <div class="d-flex justify-content-between">
{{--                                <div>--}}
{{--                                    <a href="{{ route('user.edit', $user->telegram_id) }}" class="btn btn-primary">O'zgaritirish</a>--}}
{{--                                </div>--}}
                                <div>
                                    <form action="{{ route('user.destroy', $user->telegram_id) }}" method="post">
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


            <div class="d-flex justify-content-between my-3">
                <div class="col"></div>
                <div class="col">
                    {{ $users->links() }}
                </div>
                <div class="col"></div>

            </div>

        </div>

    </div>


@endsection
