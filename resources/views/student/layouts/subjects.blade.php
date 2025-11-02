@extends('layouts.student')

@section('content')
    <div class="container-lg py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fa-solid fa-book me-2 text-warning"></i>
                My Enrolled Courses
            </h3>
        </div>

        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-gradient bg-dark text-white rounded-top-4 border-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-list-check me-2"></i>
                    Subjects You Are Enrolled In
                </h5>
            </div>
            <div class="card-body bg-light rounded-bottom-4">
                @if ($enrolledSubjects->isEmpty())
                    <div class="alert alert-info shadow-sm rounded-3 border-0">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        You have not enrolled in any subjects yet.
                    </div>
                @else
                    @php
                        $totalCredits = 0;
                        foreach ($enrolledSubjects as $subject) {
                            if ($subject->credit) {
                                $totalCredits += $subject->credit->credit_hour;
                            }
                        }
                    @endphp
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0 rounded-4 overflow-hidden">
                            <thead class="table-warning">
                                <tr>
                                    <th class="text-center" style="width:4%">#</th>
                                    <th>Subject Name</th>
                                    <th>Type</th>
                                    <th>Credits</th>
                                    <th>Teacher</th>
                                    <th>Timing</th>
                                    <th class="text-center">Drop</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrolledSubjects as $subject)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="fw-bold align-middle">{{ $subject->name }}</td>
                                        <td class="align-middle">
                                            @if ($subject->credit)
                                                <span
                                                    class="badge rounded-pill {{ $subject->credit->subject_type === 'theory' ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                                                    {{ ucfirst($subject->credit->subject_type) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if ($subject->credit)
                                                {{ $subject->credit->credit_hour }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if ($subject->class_routines->isNotEmpty())
                                                @foreach ($subject->class_routines as $routine)
                                                    <div class="mb-1">
                                                        {{ $routine->teacher->name ?? 'No Teacher Assigned' }}
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if ($subject->class_routines->isNotEmpty())
                                                @foreach ($subject->class_routines as $routine)
                                                    <div class="mb-1">
                                                        <strong>{{ $routine->day_of_week }}</strong>:
                                                        {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                                        @if ($routine->room_number)
                                                            | Room: {{ $routine->room_number }}
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Not Scheduled</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <form action="{{ route('student.drop.course', $subject->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to drop this course?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-1 shadow-sm"
                                                    style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center;"
                                                    title="Drop Course">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <div class="fs-6 fw-semibold text-dark" style="font-size: 1rem;">
                            <i class="fa-solid fa-coins text-warning me-1"></i>
                            Total Credits: <span class="text-primary">{{ $totalCredits }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
