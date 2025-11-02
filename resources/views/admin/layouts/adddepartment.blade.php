@extends('layouts.admin')

@section('content')
    <div class="container my-5">
        <div class="bg-light p-4 rounded-3 shadow-sm">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Add Department</h3>
                <p class="text-secondary">Fill in the details below to add a new department</p>
            </div>
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="mb-3 d-flex flex-column">
                    <h5 class="fw-semibold text-dark mb-2">Department Information</h5>
                    <div class="row w-100">
                        <div class="col-md-5">
                            <label for="name" class="form-label">Department Name</label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name"
                                placeholder="Enter department name" required>
                        </div>
                        <div class="col-md-5">
                            <label for="code" class="form-label">Department Code</label>
                            <input type="text" class="form-control form-control-sm" id="code" name="code"
                                placeholder="Enter department code (optional)">
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-pill">Submit</button>
                        <button type="reset" class="btn btn-danger btn-sm px-4 py-2 rounded-pill">Reset</button>
                    </div>
            </form>
        </div>
    </div>
@endsection
