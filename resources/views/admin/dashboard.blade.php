@extends('layouts.admin')

@section('content')

    <div class="row mt-5 justify-content-center">
        <!-- Students Card -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 mt-4 bg-primary bg-gradient text-white"
                style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.2)';"
                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
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
        <div class="col-md-4">
            <div class="card shadow-lg border-0 mt-4 bg-success bg-gradient text-white"
                style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.2)';"
                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
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
        <div class="col-md-4">
            <div class="card shadow-lg border-0 mt-4 bg-warning bg-gradient text-white"
                style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.2)';"
                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
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

    <div class="row mt-5">
        <!-- Classes Card -->
        <div class="col-md-4">
            <div class="card shadow-lg bg-gradient border-0 bg-info text-white"
                style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.2)';"
                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
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
        <div class="col-md-4">
            <div class="card shadow-lg bg-gradient border-0 bg-danger text-white"
                style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.2)';"
                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
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
        <div class="col-md-4">
            <div class="card shadow-lg bg-gradient border-0 bg-secondary text-white"
                style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.2)';"
                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
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
