@extends('layouts.master')

@section('title', 'Category')

@section('content')
    <div class="container-fluid px-8">
        <div class="card-mt-4">
            <div class="card-header">
                <h3 class="">Add Category</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                       <div>{{$error}}</div> 
                    @endforeach
                </div>
                    
                @endif
                <form action="{{ url('admin/add-category')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="">Category name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Slug</label>
                        <input type="text" name="slug" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">image</label>
                        <input type="file" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="">navbar_status</label>
                        <input type="checkbox" name="navbar_status">
                    </div>
                    <div class="mb-3">
                        <label for="">status</label>
                        <input type="checkbox" name="status" >
                    </div>
                   
                    <div>
                        <button type="submit" class="btn btn-info">save category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
