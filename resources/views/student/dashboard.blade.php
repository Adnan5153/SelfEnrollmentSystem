@extends('layouts.student')

@section('content')
<div class="container-lg py-4">
    <div class="mb-4">
        <h3 class="fw-bold mb-2">
            <i class="fa-solid fa-gauge-high me-2 text-success"></i>
            Student Dashboard
        </h3>
        <div class="text-secondary mb-3" style="font-size:1.1rem;">Here’s a quick snapshot of your class activities.</div>
    </div>

    {{-- 🔢 Stats Cards --}}
    <div class="row g-4 mb-4 text-center">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-lg glassmorph rounded-4 h-100">
                <div class="card-body py-4">
                    <div class="fs-2 mb-2 text-primary"><i class="fa-solid fa-book"></i></div>
                    <div class="fw-bold text-secondary mb-1">Subjects</div>
                    <div class="display-6 fw-semibold">{{ $subjectsCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-lg glassmorph rounded-4 h-100">
                <div class="card-body py-4">
                    <div class="fs-2 mb-2 text-warning"><i class="fa-solid fa-coins"></i></div>
                    <div class="fw-bold text-secondary mb-1">Total Credits</div>
                    <div class="display-6 fw-semibold">{{ $totalCredits }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-lg glassmorph rounded-4 h-100">
                <div class="card-body py-4">
                    <div class="fs-2 mb-2 text-success"><i class="fa-solid fa-person-chalkboard"></i></div>
                    <div class="fw-bold text-secondary mb-1">Teachers</div>
                    <div class="display-6 fw-semibold">{{ $teachersCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-lg glassmorph rounded-4 h-100">
                <div class="card-body py-4">
                    <div class="fs-2 mb-2 text-info"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="fw-bold text-secondary mb-1">Exams</div>
                    <div class="display-6 fw-semibold">{{ $examSchedules->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 📅 Today's Classes & Calendar --}}
    <div class="row g-4 mb-4">
        {{-- Today's Classes --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-lg glassmorph rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock me-2 text-primary"></i>Today's Classes</h6>
                </div>
                <div class="card-body pt-2 pb-3">
                    @if ($todayClasses->isEmpty())
                        <div class="p-3 text-center text-muted">No classes today!</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle table-borderless mb-0 rounded-4 overflow-hidden dashboard-table">
                                <thead class="bg-info bg-opacity-25">
                                    <tr>
                                        <th class="text-center" style="width:6%">#</th>
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todayClasses as $class)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $class->subject->name }}</td>
                                            <td>
                                                <span class="badge bg-light border text-dark fw-semibold">
                                                    {{ $class->teacher->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">
                                                    {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }} -
                                                    {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- Calendar --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-lg glassmorph rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-calendar me-2 text-info"></i>Calendar</h6>
                </div>
                <div class="card-body pt-2 pb-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🧪 Exam Schedule --}}
    <div class="card border-0 shadow-lg glassmorph rounded-4 mb-4">
        <div class="card-header bg-transparent border-0 pb-0">
            <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Upcoming Exams</h6>
        </div>
        <div class="card-body pt-2 pb-3">
            @if ($examSchedules->isEmpty())
                <div class="p-3 text-center text-muted">No upcoming exams!</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle table-borderless mb-0 rounded-4 overflow-hidden dashboard-table">
                        <thead class="bg-warning bg-opacity-25">
                            <tr>
                                <th class="text-center" style="width:6%">#</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($examSchedules as $exam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $exam->subject->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-light border text-dark fw-semibold">
                                            {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">
                                            {{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- 🚀 Quick Shortcuts --}}
    <div class="card border-0 shadow-lg glassmorph rounded-4 mb-5">
        <div class="card-header bg-transparent border-0 pb-0">
            <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bolt me-2 text-success"></i>Quick Shortcuts</h6>
        </div>
        <div class="card-body d-flex flex-wrap gap-3 pt-3 pb-2">
            <a href="{{ route('student.subjects') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">My Subjects</a>
            <a href="{{ route('student.exam.schedule') }}" class="btn btn-outline-warning rounded-pill px-4 shadow-sm">Exam Schedule</a>
            <a href="{{ route('student.classroutine') }}" class="btn btn-outline-success rounded-pill px-4 shadow-sm">Class Routine</a>
        </div>
    </div>
</div>

{{-- Some extra style for a glass/modern feel and table responsiveness --}}
<style>
    .glassmorph {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(3px);
    }
    .dashboard-table thead tr {
        border-radius: 16px !important;
        overflow: hidden;
    }
    .dashboard-table th, .dashboard-table td {
        vertical-align: middle;
        border: none !important;
    }
    .dashboard-table tr:hover td {
        background: #f6fafc !important;
        transition: background 0.2s;
    }
    @media (max-width: 768px) {
        .dashboard-table th, .dashboard-table td {
            font-size: 0.97em;
            padding: 0.5em 0.6em;
        }
        .dashboard-table thead { font-size: 1em; }
    }
</style>
@endsection

{{-- 📅 FullCalendar Script --}}
@push('scripts')
    <script>
        $(document).ready(function() {
            var SITEURL = "{{ url('/student') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#calendar').fullCalendar({
                editable: true,
                events: SITEURL + "/fullcalender",
                displayEventTime: false,
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    var title = prompt('Event Title:');
                    if (title) {
                        var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                        var end = $.fullCalendar.formatDate(end, "Y-MM-DD");

                        $.ajax({
                            url: SITEURL + "/fullcalenderAjax",
                            data: {
                                title: title,
                                start: start,
                                end: end,
                                type: 'add'
                            },
                            type: "POST",
                            success: function(data) {
                                toastr.success("Event Created Successfully");
                                $('#calendar').fullCalendar('renderEvent', {
                                    id: data.id,
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                }, true);
                                $('#calendar').fullCalendar('unselect');
                            }
                        });
                    }
                },
                eventClick: function(event) {
                    if (confirm("Do you really want to delete?")) {
                        $.ajax({
                            type: "POST",
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                id: event.id,
                                type: 'delete'
                            },
                            success: function(response) {
                                $('#calendar').fullCalendar('removeEvents', event.id);
                                toastr.success("Event Deleted Successfully");
                            }
                        });
                    }
                },
                eventDrop: function(event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

                    $.ajax({
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            type: 'update'
                        },
                        type: "POST",
                        success: function(response) {
                            toastr.success("Event Updated Successfully");
                        }
                    });
                }
            });
        });
    </script>
@endpush
