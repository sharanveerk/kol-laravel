@extends('layouts.master')

@section('title', 'Category')

@section('content')
    <div class="container-fluid px-8">
        <div class="card-mt-4">
            <div class="card-header">
                <h3 class="">Edit Category</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                       <div>{{$error}}</div> 
                    @endforeach
                </div>
                    
                @endif
                <form action="{{route('updateCategory')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$category->id}}">
                    <div class="mb-3">
                        <label for="">Category name</label>
                        <input type="text" name="name" value="{{$category->name}}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Slug</label>
                        <input type="text" name="slug" value="{{$category->slug}}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">image</label>
                        <input type="file" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="">navbar_status</label>
                        <input type="checkbox" name="navbar_status" {{$category->navbar_status=='1'? 'checked':''}}>
                    </div>
                    <div class="mb-3">
                        <label for="">status</label>
                        <input type="checkbox" value="{{$category->status}}" name="status" {{$category->status=='1'? 'checked':''}}>
                    </div>
                   
                    <div>
                        <button type="submit" class="btn btn-info">update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
