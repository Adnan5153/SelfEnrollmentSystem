@extends('layouts.teacher')

@section('content')

@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ClassRoutine;
use App\Models\AllStudent;

$teacher = Auth::user();

// Total Classes Assigned (total subject routines)
$totalClasses = ClassRoutine::where('teacher_id', $teacher->id)->count();

// Get all subject_ids the teacher teaches via routines
$subjectIds = ClassRoutine::where('teacher_id', $teacher->id)
    ->pluck('subject_id')
    ->unique()
    ->toArray();

// Get all class_ids for those subjects via class_subject pivot table
$classIds = DB::table('class_subject')
    ->whereIn('subject_id', $subjectIds)
    ->pluck('class_id')
    ->unique()
    ->filter()
    ->toArray();

// Total Students Under Supervision (all students from those classes)
$totalStudents = !empty($classIds)
    ? AllStudent::whereIn('class_id', $classIds)->count()
    : 0;

// Upcoming Classes Today (subject routines for today)
$upcomingClassesToday = ClassRoutine::where('teacher_id', $teacher->id)
    ->where('day_of_week', now()->format('l'))
    ->count();
@endphp

<div class="row mt-5">
    <!-- Total Classes Assigned -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Total Classes Assigned</h5>
                <h3 class="text-primary">{{ $totalClasses }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Students Under Supervision -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Total Students Under Supervision</h5>
                <h3 class="text-success">{{ $totalStudents }}</h3>
            </div>
        </div>
    </div>

    <!-- Upcoming Classes Today -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Upcoming Classes Today</h5>
                <h3 class="text-danger">{{ $upcomingClassesToday }}</h3>
            </div>
        </div>
    </div>
</div>

@endsection
