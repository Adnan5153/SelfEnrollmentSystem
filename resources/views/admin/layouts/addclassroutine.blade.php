@extends('layouts.admin')

@section('content')

    <div class="container mt-5 my-2 p-0">
        <!-- Add Class Routine Form -->
        <div class="bg-light p-4 rounded-3 shadow-sm mb-4">
            <!-- Success/Error Messages -->
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

            <!-- Form Header -->
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Add Class Routine</h3>
                <p class="text-secondary">Fill in the details below to add a new class routine</p>
            </div>

            <!-- Form Start -->
            <form action="{{ route('classroutines.store') }}" method="POST" id="routine-form">
                @csrf

                <div class="mb-3">
                    <h5 class="fw-semibold text-dark mb-2">Routine Information</h5>
                    <div class="row g-3">
                        <!-- Select Subject (just name) -->
                        <div class="col-md-4">
                            <label for="subject_id" class="form-label">Select Subject</label>
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                <option value="" selected disabled>Select a subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Select Teacher -->
                        <div class="col-md-4">
                            <label for="teacher_id" class="form-label">Select Teacher</label>
                            <select class="form-select" id="teacher_id" name="teacher_id" required>
                                <option value="" selected disabled>Select a teacher</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Day of the Week -->
                        <div class="col-md-4">
                            <label for="day_of_week" class="form-label">Day of the Week</label>
                            <select class="form-select" id="day_of_week" name="day_of_week" required>
                                <option value="" selected disabled>Select a day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <!-- Start Time -->
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <!-- End Time -->
                        <div class="col-md-4">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                        <!-- Room Number -->
                        <div class="col-md-4">
                            <label for="room_number" class="form-label">Room Number (Optional)</label>
                            <input type="text" class="form-control" id="room_number" name="room_number"
                                placeholder="Enter room number">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill"><i class="fa-solid fa-plus"></i>
                        Add Routine</button>
                    <button type="reset" class="btn btn-danger px-4 py-2 rounded-pill">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- List of Class Routines -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa-solid fa-list"></i> Class Routine List</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($class_routines as $routine)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $routine->subject->name ?? 'N/A' }}</td>
                            <td>{{ $routine->teacher->name ?? 'No Teacher Assigned' }}</td>
                            <td>{{ ucfirst($routine->day_of_week) }}</td>
                            <td>{{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} -
                                {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}</td>
                            <td>{{ $routine->room_number ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('classroutines.edit', $routine->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('classroutines.destroy', $routine->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
