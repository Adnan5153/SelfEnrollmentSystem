@extends('layouts.teacher')

@section('content')
    <h3 class="mb-4 mt-4">Class Schedule</h3>

    @forelse ($groupedByDay as $day => $routines)
        <div class="card shadow mb-4 w-100">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-calendar-day"></i> {{ $day }}
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routines as $routine)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $routine->subject->name ?? 'N/A' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                </td>
                                <td>{{ $routine->room_number ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            No class routine assigned for you yet.
        </div>
    @endforelse
@endsection
