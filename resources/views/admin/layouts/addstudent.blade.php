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

            <!-- Form Header -->
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Add Student Form</h3>
                <p class="text-secondary">Fill in the details below to add a new student</p>
            </div>

            <!-- Form Start -->
            <form action="{{ route('register.student.and.parent.store') }}" method="POST">
                @csrf

                <!-- Student Information Section -->
                <div class="mb-3">
                    <h5 class="fw-semibold text-dark mb-2">Student Information</h5>
                    <div class="row g-2">
                        <!-- First Name -->
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control form-control-sm" id="first_name" name="first_name"
                                required>
                        </div>
                        <!-- Last Name -->
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control form-control-sm" id="last_name" name="last_name"
                                required>
                        </div>
                        <!-- Class & Section -->
                        <div class="col-md-4">
                            <label for="class_id" class="form-label">Class & Section</label>
                            <select class="form-select form-select-sm" id="class_id" name="class_id" required>
                                <option value="">Select Class & Section</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }} - {{ $class->section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Gender -->
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select form-select-sm" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <!-- Date of Birth -->
                        <div class="col-md-4">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control form-control-sm" id="date_of_birth"
                                name="date_of_birth" required>
                        </div>
                        <!-- Student ID -->
                        <div class="col-md-4">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="number" class="form-control form-control-sm" id="student_id" name="student_id"
                                required>
                        </div>
                        <!-- Department -->
                        <div class="col-md-4">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select form-select-sm" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Religion -->
                        <div class="col-md-4">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="form-control form-control-sm" id="religion" name="religion"
                                required>
                        </div>
                        <!-- Email Address -->
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control form-control-sm" id="email" name="email"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-pill">Submit</button>
                </div>
            </form>

        </div>
    </div>
@endsection
