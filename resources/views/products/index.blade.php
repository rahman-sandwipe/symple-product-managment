@extends('app')
@section('title')
    Data Record
@endsection
@section('content')
<main class="card mb-5">
    <div class="card-header">
        <div class="row">
            <div class="col-3">
                <h2>Products</h2>
            </div>
            <div class="col-6">
                <form action="{{ url('/products') }}" method="GET" class="form-inline my-2">
                    <input class="form-control mr-2" name="search" value="{{ request()->get('search') }}" placeholder="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
            <div class="col-3 text-right">
                <a class="btn btn-primary" href="{{ route('products.create') }}"> Add New Product</a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <table class="table table-striped">
            {{-- <table> --}}
            <tr>
                <th>SL</th>
                <th>Product Id</th>
                <th>IMG</th>
                <th>
                    <a href="{{ route('products.index', ['sort_by' => 'name', 'sort_order' => $sortBy === 'name' && $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                        Sort by Name {{ $sortBy === 'name' ? ($sortOrder === 'asc' ? '↓' : '↑') : '' }}
                    </a>
                </th>
                <th>Description</th>
                <th>
                    <a href="{{ route('products.index', ['sort_by' => 'price', 'sort_order' => $sortBy === 'price' && $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                        Sort by Price {{ $sortBy === 'price' ? ($sortOrder === 'asc' ? '↓' : '↑') : '' }}
                    </a>
                </th>
                <th>Stock</th>
                <th>Action</th>
            </tr>

            @foreach ($products as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product_id }}</td>
                <td><img src="{{ asset($item->image) }}" width="50" alt="product-{{ $item->image }}"></td>
                <td>{{ $item->name }}</td>
                <td>{!! $item->description !!}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->stock }}</td>

                <td style="width: 27%">
                    <form action="{{ route('products.destroy',$item->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('products.show',$item->id) }}">Show</a>
    
                        <a class="btn btn-primary" href="{{ route('products.edit',$item->id) }}">Edit</a>
                        
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection