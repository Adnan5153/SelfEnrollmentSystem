@extends('layouts.admin')

@section('content')
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="fa-regular fa-clipboard"></i> Class Routine List
                </h5>
                <a href="{{ route('classroutines.create') }}" class="btn btn-success btn-sm rounded-pill shadow-sm">
                    <i class="fa-solid fa-plus"></i> Add Routine
                </a>
            </div>
            <div class="card-body bg-light rounded-bottom-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-info">
                            <tr>
                                <th class="text-center text-black" style="width:5%;">#</th>
                                <th class="text-black">Subject</th>
                                <th class="text-black">Teacher</th>
                                <th class="text-black">Day</th>
                                <th class="text-black">Time</th>
                                <th class="text-black">Room</th>
                                <th class="text-center text-black" style="width:8%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($class_routines as $routine)
                                <tr>
                                    <td class="text-center fw-bold text-black">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold text-black">{{ $routine->subject->name ?? 'N/A' }}</td>
                                    <td class="text-black">{{ $routine->teacher->name ?? 'No Teacher Assigned' }}</td>
                                    <td class="text-black">{{ ucfirst($routine->day_of_week) }}</td>
                                    <td class="text-black">
                                        {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}
                                        <span class="text-black">-</span>
                                        {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                    </td>
                                    <td class="text-black">{{ $routine->room_number ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-outline-warning btn-sm rounded-circle shadow-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editClassRoutineModal-{{ $routine->id }}"
                                            title="Edit Routine">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('classroutines.destroy', $routine->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm rounded-circle shadow-sm ms-1"
                                                onclick="return confirm('Are you sure you want to delete this class routine?')"
                                                title="Delete Routine">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-black-50">No class routines available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Render the edit modals for each routine --}}
                @foreach($class_routines as $routine)
                    @include('admin.layouts.modal.editclassroutine', [
                        'class_routine' => $routine,
                        'subjects' => $subjects ?? \App\Models\Subject::with('class')->get(),
                        'teachers' => $teachers ?? \App\Models\Teacher::all()
                    ])
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
