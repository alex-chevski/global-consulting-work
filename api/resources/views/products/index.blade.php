@extends('layouts.app')

@section('content')
    <div class="row">

        <table class="table table-main col">
            <thead>
                <tr>
                    <th class="text-muted title-table" scope="col">АРТИКУЛ</th>
                    <th class="text-muted title-table" scope="col">НАЗВАНИЕ</th>
                    <th class="text-muted title-table" scope="col">СТАТУС</th>
                    <th class="text-muted title-table" scope="col">АТРИБУТЫ</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($products as $product)
                    <tr>
                        <td class="text-muted text-decoration-underline"><a type="button"
                                id="showProduct">{{ $product->article }}</a>
                        </td>
                        <td class="text-muted"> {{ $product->name }} </td>
                        <td class="text-muted"> {{ $product->status === 'available' ? 'Доступен' : 'Не доступен' }} </td>
                        @include('products._attributes', ['product' => $product])
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <button id="createProduct" class="btn btn-info col" type="button">Добавить</button>

    {{-- @include('products.create', ['statuses' => $statuses]) --}}
@endsection
