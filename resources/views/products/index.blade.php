@extends('layouts.main')

@section('content')


    <div class="card mb-4">
        <div class="card-body">

            <div class="container-fluid">
                <div class="row justify-content-between">
                    <div class="h3">Maxsulotlar</div>
                    <div>
                        <a href="{{ route('product.create') }}" class="btn btn-success"><b>+</b> Qo'shish</a>
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
                    <td>O'lchov</td>
                    <td>Narxi</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }} @if($product->status) <div class="mb-2 mr-2 badge badge-pill badge-success">Yangi</div> @endif</td>
                        <td><img width="100" src="{{ $product->image }}" alt=""></td>
                        <td>{{ $product->unit }}</td>
                        <td>{{ number_format($product->price) }} so'm</td>
                        <td width="250px">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary">O'zgaritirish</a>
                                </div>
                                <div>
                                    <form action="{{ route('product.destroy', $product->id) }}" method="post">
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


            {{ $products->links() }}

        </div>

    </div>


@endsection
