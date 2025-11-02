<!-- resources/views/admin/layouts/modal/editexamschedule.blade.php -->
<div class="modal fade" id="editExamScheduleModal-{{ $examSchedule->id }}" tabindex="-1" aria-labelledby="editExamScheduleLabel-{{ $examSchedule->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                <h6 class="modal-title d-flex align-items-center gap-2 mb-0" id="editExamScheduleLabel-{{ $examSchedule->id }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Edit Exam Schedule
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('examschedule.update', $examSchedule->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body bg-light py-3 px-3">
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-2">
                        <label for="class_id-{{ $examSchedule->id }}" class="form-label small">Select Class</label>
                        <select class="form-select form-select-sm" id="class_id-{{ $examSchedule->id }}" name="class_id" required>
                            <option value="" selected disabled>Select a class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $class->id == $examSchedule->class_id ? 'selected' : '' }}>
                                    {{ $class->class_name }} - {{ $class->section }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="subject_id-{{ $examSchedule->id }}" class="form-label small">Select Subject</label>
                        <select class="form-select form-select-sm" id="subject_id-{{ $examSchedule->id }}" name="subject_id" required>
                            <option value="" selected disabled>Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $subject->id == $examSchedule->subject_id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="exam_date-{{ $examSchedule->id }}" class="form-label small">Exam Date</label>
                            <input type="date" class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="exam_date-{{ $examSchedule->id }}" name="exam_date"
                                   value="{{ $examSchedule->exam_date }}" required>
                        </div>
                        <div class="col-3">
                            <label for="start_time-{{ $examSchedule->id }}" class="form-label small">Start Time</label>
                            <input type="time" class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="start_time-{{ $examSchedule->id }}" name="start_time"
                                   value="{{ $examSchedule->start_time }}" required>
                        </div>
                        <div class="col-3">
                            <label for="end_time-{{ $examSchedule->id }}" class="form-label small">End Time</label>
                            <input type="time" class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="end_time-{{ $examSchedule->id }}" name="end_time"
                                   value="{{ $examSchedule->end_time }}" required>
                        </div>
                    </div>
                    <div class="mb-2 mt-2">
                        <label for="room_number-{{ $examSchedule->id }}" class="form-label small">Room Number <span class="text-muted small">(Optional)</span></label>
                        <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                               id="room_number-{{ $examSchedule->id }}" name="room_number"
                               value="{{ $examSchedule->room_number }}" placeholder="Enter room number">
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-save"></i> Update Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
