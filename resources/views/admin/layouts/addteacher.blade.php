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
                <h3 class="fw-bold text-dark">Add Teacher Form</h3>
                <p class="text-secondary">Fill in the details below to add a new teacher</p>
            </div>

            <!-- Form Start -->
            <form action="{{ route('addteacher.store') }}" method="POST">
                @csrf

                <!-- Teacher Information Section -->
                <div class="mb-3">
                    <h5 class="fw-semibold text-dark mb-2">Teacher Information</h5>
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
                            <label for="class_section" class="form-label">Class & Section</label>
                            <select class="form-select form-select-sm" id="class_section" name="class_section" required>
                                <option value="">Select Class & Section</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}|{{ $class->section }}">
                                        {{ $class->class_name }} - {{ $class->section }}
                                    </option>
                                @endforeach
                            </select>
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
                        <!-- Joining Date -->
                        <div class="col-md-4">
                            <label for="joining_date" class="form-label">Joining Date</label>
                            <input type="date" class="form-control form-control-sm" id="joining_date" name="joining_date"
                                required>
                        </div>
                        <!-- NID Number -->
                        <div class="col-md-4">
                            <label for="nid_number" class="form-label">NID Number</label>
                            <input type="number" class="form-control form-control-sm" id="nid_number" name="nid_number">
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
                        <!-- Phone Number -->
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="number" class="form-control form-control-sm" id="phone" name="phone"
                                required>
                        </div>
                        <!-- Address -->
                        <div class="col-md-4">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control form-control-sm" id="address" name="address"
                                required>
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
