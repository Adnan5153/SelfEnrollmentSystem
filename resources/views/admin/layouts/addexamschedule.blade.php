@extends('layouts.admin')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-dark bg-gradient text-white rounded-top-4 px-4 py-3">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                        <i class="fa-solid fa-calendar-days"></i> Add Exam Schedule
                    </h5>
                </div>
                <div class="card-body bg-light rounded-bottom-4 p-4">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fa-solid fa-circle-exclamation me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form Header --}}
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark mb-0">Add Exam Schedule</h3>
                        <p class="text-secondary mb-0">Fill in the details below to add a new exam schedule</p>
                    </div>

                    {{-- Form Start --}}
                    <form action="{{ route('examschedule.store') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="mb-4">
                            <h5 class="fw-semibold text-dark mb-2">
                                <i class="fa-solid fa-file-circle-plus text-primary me-2"></i>
                                Exam Schedule Information
                            </h5>
                            <div class="row g-3">
                                <!-- Select Class -->
                                <div class="col-12 col-md-4">
                                    <label for="class_id" class="form-label small">Select Class</label>
                                    <select class="form-select form-select-sm rounded-3 shadow-sm" id="class_id" name="class_id" required>
                                        <option value="" selected disabled>Select a class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }} - {{ $class->section }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Select Subject (ALL subjects, not filtered by class) -->
                                <div class="col-12 col-md-4">
                                    <label for="subject_id" class="form-label small">Select Subject</label>
                                    <select class="form-select form-select-sm rounded-3 shadow-sm" id="subject_id" name="subject_id" required>
                                        <option value="" selected disabled>Select a subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Exam Date -->
                                <div class="col-12 col-md-4">
                                    <label for="exam_date" class="form-label small">Exam Date</label>
                                    <input type="date" class="form-control form-control-sm rounded-3 shadow-sm" id="exam_date" name="exam_date" required>
                                </div>
                                <!-- Start Time -->
                                <div class="col-12 col-md-4">
                                    <label for="start_time" class="form-label small">Start Time</label>
                                    <input type="time" class="form-control form-control-sm rounded-3 shadow-sm" id="start_time" name="start_time" required>
                                </div>
                                <!-- End Time -->
                                <div class="col-12 col-md-4">
                                    <label for="end_time" class="form-label small">End Time</label>
                                    <input type="time" class="form-control form-control-sm rounded-3 shadow-sm" id="end_time" name="end_time" required>
                                </div>
                                <!-- Room Number -->
                                <div class="col-12 col-md-4">
                                    <label for="room_number" class="form-label small">Room Number (Optional)</label>
                                    <input type="text" class="form-control form-control-sm rounded-3 shadow-sm" id="room_number" name="room_number" placeholder="Enter room number">
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-plus"></i> Add Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
