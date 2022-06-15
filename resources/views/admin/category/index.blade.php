@extends('layouts.master')

@section('title', 'Category')

@section('content')
    <div class="container">
        <div class="row">
        <div class="card-header mt-4">
            <h1>view Categories <a href="{{ url('admin/add-category') }}" class="btn btn-primary float-end">Add Category</a>
            </h1>


        </div>
        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($category as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td><img src="{{ asset('uploads/category/'.$item->image)}}" width="50px" height="50px" alt="image"></td>
                        <td>{{ $item->status=='1' ? 'shown':'hidden' }}</td>
                        <td>
                            <a href="{{ url('admin/edit-category?id='.$item->id) }}" class="btn btn-success float-end">Edit</a>
                        </td>
                       
                    </tr>
                        
                    @endforeach

                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
