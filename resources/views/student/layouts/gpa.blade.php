@extends('layouts.student')

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4 mt-3 py-4">
        <i class="fa-solid fa-list fa-xl text-primary"></i>
        <h3 class="fw-bold mb-0">Grade Book</h3>
    </div>

    <div class="card shadow-lg border-0 rounded-4 mb-5">
        <div class="card-header bg-gradient bg-dark text-white rounded-top-4 border-0 py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                <i class="fa-solid fa-table-list"></i> List of Grades
            </h5>
        </div>
        <div class="card-body bg-light rounded-bottom-4 p-4">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle rounded-4 overflow-hidden mb-0">
                    <thead class="table-success">
                        <tr class="align-middle">
                            <th class="text-center" style="width: 4%">#</th>
                            <th>Marks Range</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades as $grade)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark rounded-pill px-6">
                                        {{ $grade->min_marks }} - {{ $grade->max_marks }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold fs-10 text-dark">{{ $grade->grade }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $grade->remarks ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
