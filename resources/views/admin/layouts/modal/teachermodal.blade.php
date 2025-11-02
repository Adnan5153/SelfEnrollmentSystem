@if (!empty($teacher->id))
<!-- resources/views/admin/layouts/modal/teachermodal.blade.php -->
<div class="modal fade" id="editTeacherModal-{{ $teacher->id }}" tabindex="-1" aria-labelledby="editTeacherLabel-{{ $teacher->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                <h6 class="modal-title d-flex align-items-center gap-2 mb-0" id="editTeacherLabel-{{ $teacher->id }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Edit Teacher Details
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('allteachers.update', $teacher->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body bg-light py-3 px-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="first_name-{{ $teacher->id }}" class="form-label small">First Name</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="first_name-{{ $teacher->id }}" name="first_name"
                                value="{{ $teacher->first_name }}" required>
                        </div>
                        <div class="col-6">
                            <label for="last_name-{{ $teacher->id }}" class="form-label small">Last Name</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="last_name-{{ $teacher->id }}" name="last_name"
                                value="{{ $teacher->last_name }}" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-0">
                        <div class="col-6">
                            <label for="class_id-{{ $teacher->id }}" class="form-label small">Class ID</label>
                            <input type="number" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="class_id-{{ $teacher->id }}" name="class_id"
                                value="{{ $teacher->class_id }}" required>
                        </div>
                        <div class="col-6">
                            <label for="section-{{ $teacher->id }}" class="form-label small">Section</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="section-{{ $teacher->id }}" name="section"
                                value="{{ $teacher->section }}" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-0">
                        <div class="col-6">
                            <label for="subject-{{ $teacher->id }}" class="form-label small">Subject</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="subject-{{ $teacher->id }}" name="subject"
                                value="{{ $teacher->subject }}" required>
                        </div>
                        <div class="col-6">
                            <label for="gender-{{ $teacher->id }}" class="form-label small">Gender</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="gender-{{ $teacher->id }}" name="gender"
                                value="{{ $teacher->gender }}" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-0">
                        <div class="col-6">
                            <label for="date_of_birth-{{ $teacher->id }}" class="form-label small">Date of Birth</label>
                            <input type="date" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="date_of_birth-{{ $teacher->id }}" name="date_of_birth"
                                value="{{ $teacher->date_of_birth }}" required>
                        </div>
                        <div class="col-6">
                            <label for="joining_date-{{ $teacher->id }}" class="form-label small">Joining Date</label>
                            <input type="date" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="joining_date-{{ $teacher->id }}" name="joining_date"
                                value="{{ $teacher->joining_date }}" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-0">
                        <div class="col-6">
                            <label for="nid_number-{{ $teacher->id }}" class="form-label small">NID Number</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="nid_number-{{ $teacher->id }}" name="nid_number"
                                value="{{ $teacher->nid_number }}">
                        </div>
                        <div class="col-6">
                            <label for="religion-{{ $teacher->id }}" class="form-label small">Religion</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="religion-{{ $teacher->id }}" name="religion"
                                value="{{ $teacher->religion }}" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-0">
                        <div class="col-6">
                            <label for="email-{{ $teacher->id }}" class="form-label small">Email Address</label>
                            <input type="email" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="email-{{ $teacher->id }}" name="email"
                                value="{{ $teacher->email }}" required>
                        </div>
                        <div class="col-6">
                            <label for="phone-{{ $teacher->id }}" class="form-label small">Phone Number</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="phone-{{ $teacher->id }}" name="phone"
                                value="{{ $teacher->phone }}" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-0">
                        <div class="col-12">
                            <label for="address-{{ $teacher->id }}" class="form-label small">Address</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="address-{{ $teacher->id }}" name="address"
                                value="{{ $teacher->address }}" required>
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
@endif
