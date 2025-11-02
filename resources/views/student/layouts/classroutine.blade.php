@extends('layouts.student')

@section('content')
    <div class="container-lg py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fa-solid fa-clipboard-list me-2 text-primary"></i>
                My Class Routine
            </h3>
        </div>
        @if ($class_routines->isEmpty())
            <div class="alert alert-warning shadow-sm rounded-3 border-0">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                No class routine is available.
            </div>
        @else
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-dark bg-gradient text-white rounded-top-4 border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-solid fa-clipboard-list me-2"></i>
                        Class Routine
                    </h5>
                </div>
                <div class="card-body bg-light rounded-bottom-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0 rounded-4 overflow-hidden">
                            <thead class="table-info">
                                <tr>
                                    <th class="text-center" style="width:4%">#</th>
                                    <th>Day</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Time</th>
                                    <th>Room</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class_routines as $routine)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">
                                            <span class="badge bg-light text-primary border px-3 py-2 fs-6">{{ $routine->day_of_week }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-white border text-dark fw-bold px-3 py-2 fs-6">
                                                {{ $routine->subject->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">
                                                {{ $routine->teacher->name ?? 'No Teacher Assigned' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 fs-6">
                                                {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}
                                                -
                                                {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-10 text-dark fw-semibold px-3 py-2 fs-6">
                                                {{ $routine->room_number ?? 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-muted small">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Times are shown in 12-hour format. Tap a row for more details (if supported).
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
