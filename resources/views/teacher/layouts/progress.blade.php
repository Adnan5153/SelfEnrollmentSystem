@extends('layouts.teacher')

@section('content')
    <h3 class="mb-4 mt-5">Student Performance</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    @php
        $groupedMarks = $marks->groupBy(function ($mark) {
            $student = $mark->student;
            if (!$student) {
                return 'Unknown-Unknown';
            }
            return ($student->year ?? 'Unknown') . '-' . ($student->section ?? 'Unknown');
        });
    @endphp

    @foreach ($groupedMarks as $groupKey => $group)
        @php
            $student = $group->first()->student;
            [$year, $section] = explode('-', $groupKey);
        @endphp

        <div class="card shadow mb-4 w-100">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-chart-line"></i> Student Performance: {{ $year }} - {{ $section }}
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Marks</th>
                                <th>GPA</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($group as $mark)
                                @php
                                    $student = $mark->student;
                                    $grade = $grades->first(
                                        fn($g) => $mark->marks >= $g->min_marks && $mark->marks <= $g->max_marks,
                                    );
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student ? $student->id : 'N/A' }}</td>
                                    <td>{{ $student ? $student->name : 'Unknown Student' }}</td>
                                    <td>{{ $mark->marks }}</td>
                                    <td>{{ $grade->grade ?? 'N/A' }}</td>
                                    <td>{{ $mark->remarks ?? 'N/A' }}</td>
                                    <td>
                                        <!-- ✏️ Edit Button -->
                                        <button class="btn btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $mark->id }}"
                                            title="Edit marks for {{ $student ? $student->name : 'student' }}"
                                            aria-label="Edit marks for {{ $student ? $student->name : 'student' }}">
                                            <i class="fa-solid fa-pen-to-square" style="color: #FFD43B;"></i>
                                        </button>

                                        <!-- 🗑️ Delete Form -->
                                        <form action="{{ route('progress.destroy', $mark->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this mark?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm"
                                                title="Delete marks for {{ $student ? $student->name : 'student' }}"
                                                aria-label="Delete marks for {{ $student ? $student->name : 'student' }}">
                                                <i class="fa-solid fa-trash" style="color: #ff0000;"></i>
                                            </button>
                                        </form>

                                        <!-- 📝 Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $mark->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel{{ $mark->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('progress.update', $mark->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title" id="editModalLabel{{ $mark->id }}">
                                                                Edit Marks for
                                                                {{ $student ? $student->name : 'Unknown Student' }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Marks</label>
                                                                <input type="number" name="marks" class="form-control"
                                                                    value="{{ $mark->marks }}" required min="0"
                                                                    max="100">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Remarks</label>
                                                                <textarea name="remarks" class="form-control">{{ $mark->remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Save
                                                                Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Edit Modal -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
@endsection
