<!-- resources/views/admin/layouts/modal/editclassroutine.blade.php -->
@if(isset($class_routine) && isset($subjects) && isset($teachers))
<div class="modal fade" id="editClassRoutineModal-{{ $class_routine->id }}" tabindex="-1" aria-labelledby="editClassRoutineLabel-{{ $class_routine->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-dark bg-gradient text-white rounded-top-4">
                <h6 class="modal-title d-flex align-items-center gap-2 mb-0" id="editClassRoutineLabel-{{ $class_routine->id }}">
                    <i class="fa-regular fa-clipboard"></i>
                    Edit Class Routine
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('classroutines.update', $class_routine->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body bg-light py-3 px-3">
                    <div class="mb-3">
                        <label for="subject_id-{{ $class_routine->id }}" class="form-label small">Subject</label>
                        <select class="form-select form-select-sm rounded-3 shadow-sm" id="subject_id-{{ $class_routine->id }}" name="subject_id" required>
                            <option value="" disabled>Select subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $subject->id == $class_routine->subject_id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                    @if($subject->class) ({{ $subject->class->class_name }}{{ $subject->class->section ? ' - '.$subject->class->section : '' }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_id-{{ $class_routine->id }}" class="form-label small">Teacher</label>
                        <select class="form-select form-select-sm rounded-3 shadow-sm" id="teacher_id-{{ $class_routine->id }}" name="teacher_id" required>
                            <option value="" disabled>Select teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $teacher->id == $class_routine->teacher_id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="day_of_week-{{ $class_routine->id }}" class="form-label small">Day</label>
                        <select class="form-select form-select-sm rounded-3 shadow-sm" id="day_of_week-{{ $class_routine->id }}" name="day_of_week" required>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <option value="{{ $day }}" {{ $class_routine->day_of_week === $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="start_time-{{ $class_routine->id }}" class="form-label small">Start Time</label>
                            <input type="time" class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="start_time-{{ $class_routine->id }}" name="start_time"
                                   value="{{ $class_routine->start_time }}" required>
                        </div>
                        <div class="col-6">
                            <label for="end_time-{{ $class_routine->id }}" class="form-label small">End Time</label>
                            <input type="time" class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="end_time-{{ $class_routine->id }}" name="end_time"
                                   value="{{ $class_routine->end_time }}" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="room_number-{{ $class_routine->id }}" class="form-label small">Room Number (Optional)</label>
                        <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                               id="room_number-{{ $class_routine->id }}" name="room_number"
                               value="{{ $class_routine->room_number }}" placeholder="Enter room number">
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
