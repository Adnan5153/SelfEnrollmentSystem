@extends('layouts.admin')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-12">
            <div class="d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-calendar-days fa-xl text-primary"></i>
                <h3 class="fw-bold mb-0">Exam Schedule List</h3>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @php
                $grouped = $examSchedules->groupBy(function ($item) {
                    return $item->class_id . '-' . $item->class->section;
                });
            @endphp

            @foreach ($grouped as $key => $group)
                @php
                    $first = $group->first();
                    $className = $first->class->class_name ?? 'Unknown';
                    $section = $first->class->section ?? '';
                @endphp

                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex align-items-center gap-2 px-4 py-3">
                        <h5 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                            <i class="fa-solid fa-school"></i>
                            Class {{ $className }} - {{ $section }}
                        </h5>
                    </div>
                    <div class="card-body bg-light rounded-bottom-4">
                        <div class="table-responsive rounded-4">
                            <table class="table align-middle mb-0 table-hover small">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-center text-black" style="width: 6%;">#</th>
                                        <th class="text-black">Subject</th>
                                        <th class="text-black">Exam Date</th>
                                        <th class="text-black">Time</th>
                                        <th class="text-black">Room</th>
                                        <th class="text-center text-black" style="width: 10%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group as $schedule)
                                        <tr>
                                            <td class="text-center text-black fw-bold">{{ $loop->iteration }}</td>
                                            <td class="text-black fw-semibold">{{ $schedule->subject->name ?? 'N/A' }}</td>
                                            <td class="text-black">{{ \Carbon\Carbon::parse($schedule->exam_date)->format('d M Y') }}</td>
                                            <td class="text-black">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                            </td>
                                            <td class="text-black">{{ $schedule->room_number ?? 'Not Assigned' }}</td>
                                            <td class="text-center">
                                                <!-- Edit Modal Trigger -->
                                                <button type="button"
                                                    class="btn btn-outline-warning btn-sm rounded-circle shadow-sm me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editExamScheduleModal-{{ $schedule->id }}"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <!-- Delete -->
                                                <form action="{{ route('examschedule.destroy', $schedule->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                                        onclick="return confirm('Are you sure?')"
                                                        title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Include edit modals for each schedule --}}
            @foreach ($grouped as $key => $group)
                @foreach ($group as $schedule)
                    @include('admin.layouts.modal.editexamschedule', [
                        'examSchedule' => $schedule,
                        'classes' => $classes ?? [],
                        'subjects' => $subjects ?? [],
                    ])
                @endforeach
            @endforeach

        </div>
    </div>
@endsection
