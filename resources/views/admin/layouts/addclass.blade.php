@extends('layouts.admin')

@section('content')
    <div class="container my-5">
        <!-- Form Container -->
        <div class="bg-light p-4 rounded-3 shadow-sm">

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Header -->
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Add Class</h3>
                <p class="text-secondary">Fill in the details below to add a new class</p>
            </div>

            <!-- Form Start -->
            <form action="{{ route('classes.store') }}" method="POST">
                @csrf

                <!-- Class Information Section -->
                <div class="mb-3">
                    <h5 class="fw-semibold text-dark mb-2">Class Information</h5>
                    <div class="row g-2">
                        <!-- Class Name -->
                        <div class="col-md-4">
                            <label for="class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control form-control-sm" id="class_name" name="class_name"
                                placeholder="Enter class name" required>
                        </div>
                        <!-- Section -->
                        <div class="col-md-4">
                            <label for="section" class="form-label">Section</label>
                            <input type="text" class="form-control form-control-sm" id="section" name="section"
                                placeholder="Enter section (optional)">
                        </div>
                        <!-- Capacity -->
                        <div class="col-md-4">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control form-control-sm" id="capacity" name="capacity"
                                placeholder="Enter maximum capacity (optional)">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-pill">Submit</button>
                    <button type="reset" class="btn btn-danger btn-sm px-4 py-2 rounded-pill">Reset</button>
                </div>
            </form>
        </div>
    </div>
@endsection
