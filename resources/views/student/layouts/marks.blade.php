@extends('layouts.student')

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4 mt-3 py-4">
        <i class="fa-solid fa-chart-simple fa-xl text-primary"></i>
        <h3 class="fw-bold mb-0">My Marks</h3>
    </div>

    <div class="card shadow-lg border-0 rounded-4 mb-5">
        <div class="card-header bg-gradient bg-dark text-white rounded-top-4 border-0 py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check"></i> Marks Summary
            </h5>
        </div>
        <div class="card-body bg-light rounded-bottom-4 p-4">
            @if ($marks->isEmpty())
                <div class="alert alert-info d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-info fa-lg"></i>
                    <span>No marks available at this time.</span>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle rounded-4 overflow-hidden mb-0">
                        <thead class="table-success">
                            <tr class="align-middle">
                                <th class="text-center" style="width: 4%">#</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Obtained Marks</th>
                                <th>GPA</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($marks as $mark)
                                @php
                                    $grade = $grades->first(
                                        fn($g) => $mark->marks >= $g->min_marks && $mark->marks <= $g->max_marks,
                                    );
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $mark->subject->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">
                                            {{ $mark->teacher->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $mark->marks }}</span>
                                    </td>
                                    <td>
                                        @if (isset($grade->grade))
                                            <span
                                                class="badge bg-primary-subtle text-primary-emphasis px-3 py-2 rounded-pill">
                                                {{ $grade->grade }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-secondary rounded-pill">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $mark->remarks ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
