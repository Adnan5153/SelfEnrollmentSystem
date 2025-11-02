<!-- resources/views/admin/layouts/modal/edit.blade.php -->
<div class="modal fade" id="editStudentModal-{{ $student->student_id }}" tabindex="-1" aria-labelledby="editStudentLabel-{{ $student->student_id }}" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                <h6 class="modal-title d-flex align-items-center gap-2 mb-0" id="editStudentLabel-{{ $student->student_id }}">
                    <i class="fa-solid fa-user-pen"></i>
                    Edit Student
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('allstudent.update', $student->student_id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body bg-light py-3 px-3">
                    <!-- Student Info Section -->
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa-solid fa-user-graduate me-2 text-primary"></i>
                            <span class="fw-semibold text-primary small">Student Info</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="first_name-{{ $student->student_id }}" class="form-label small">First Name</label>
                                <input type="text" class="form-control form-control-sm rounded-3 shadow-sm" id="first_name-{{ $student->student_id }}" name="first_name" value="{{ $student->first_name }}" required>
                            </div>
                            <div class="col-6">
                                <label for="last_name-{{ $student->student_id }}" class="form-label small">Last Name</label>
                                <input type="text" class="form-control form-control-sm rounded-3 shadow-sm" id="last_name-{{ $student->student_id }}" name="last_name" value="{{ $student->last_name }}" required>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-4">
                                <label for="class-{{ $student->student_id }}" class="form-label small">Class</label>
                                <input type="text" class="form-control form-control-sm rounded-3 shadow-sm" id="class-{{ $student->student_id }}" name="class" value="{{ $student->class }}" required>
                            </div>
                            <div class="col-4">
                                <label for="section-{{ $student->student_id }}" class="form-label small">Section</label>
                                <input type="text" class="form-control form-control-sm rounded-3 shadow-sm" id="section-{{ $student->student_id }}" name="section" value="{{ $student->section }}" required>
                            </div>
                            <div class="col-4">
                                <label for="gender-{{ $student->student_id }}" class="form-label small">Gender</label>
                                <input type="text" class="form-control form-control-sm rounded-3 shadow-sm" id="gender-{{ $student->student_id }}" name="gender" value="{{ $student->gender }}" required>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <label for="date_of_birth-{{ $student->student_id }}" class="form-label small">Date of Birth</label>
                                <input type="date" class="form-control form-control-sm rounded-3 shadow-sm" id="date_of_birth-{{ $student->student_id }}" name="date_of_birth" value="{{ $student->date_of_birth }}" required>
                            </div>
                            <div class="col-6">
                                <label for="religion-{{ $student->student_id }}" class="form-label small">Religion</label>
                                <input type="text" class="form-control form-control-sm rounded-3 shadow-sm" id="religion-{{ $student->student_id }}" name="religion" value="{{ $student->religion }}" required>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-12">
                                <label for="email-{{ $student->student_id }}" class="form-label small">Email</label>
                                <input type="email" class="form-control form-control-sm rounded-3 shadow-sm" id="email-{{ $student->student_id }}" name="email" value="{{ $student->email }}" required>
                            </div>
                        </div>
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
