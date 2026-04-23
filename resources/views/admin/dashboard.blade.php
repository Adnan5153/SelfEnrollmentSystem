@extends('layouts.admin')

@section('content')

    <!-- First row -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mt-5">
        <!-- Students Card -->
        <div class="col">
            <div class="card shadow-lg border-0 bg-primary bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-user-graduate fa-2x"></i>
                    <h6 class="mt-2">Total Students</h6>
                    <h4 class="fw-bold">
                        {{ \App\Models\AllStudent::count() }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- Teachers Card -->
        <div class="col">
            <div class="card shadow-lg border-0 bg-success bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-chalkboard-teacher fa-2x"></i>
                    <h6 class="mt-2">Total Teachers</h6>
                    <h4 class="fw-bold">
                        {{ \App\Models\AllTeacher::count() }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- Parents Card -->
        <div class="col">
            <div class="card shadow-lg border-0 bg-warning bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-users fa-2x"></i>
                    <h6 class="mt-2">Total Parents</h6>
                    <h4 class="fw-bold">
                        {{ \App\Models\ParentModel::count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Second row -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mt-4">
        <!-- Classes Card -->
        <div class="col">
            <div class="card shadow-lg bg-gradient border-0 bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-school fa-2x"></i>
                    <h6 class="mt-2">Total Classes</h6>
                    <h4 class="fw-bold">
                        {{ \App\Models\ClassModel::count() }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- Subjects Card -->
        <div class="col">
            <div class="card shadow-lg bg-gradient border-0 bg-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-book fa-2x"></i>
                    <h6 class="mt-2">Total Subjects</h6>
                    <h4 class="fw-bold">
                        {{ \App\Models\Subject::count() }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- Exam Schedules Card -->
        <div class="col">
            <div class="card shadow-lg bg-gradient border-0 bg-secondary text-white h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-calendar-alt fa-2x"></i>
                    <h6 class="mt-2">Total Exam Schedules</h6>
                    <h4 class="fw-bold">
                        {{ \App\Models\ExamSchedule::count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
@endsection
