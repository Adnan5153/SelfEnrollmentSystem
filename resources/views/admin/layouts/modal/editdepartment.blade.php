<!-- resources/views/admin/layouts/modal/editdepartment.blade.php -->
<div class="modal fade" id="editDepartmentModal-{{ $department->id }}" tabindex="-1"
    aria-labelledby="editDepartmentLabel-{{ $department->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                <h6 class="modal-title d-flex align-items-center gap-2 mb-0"
                    id="editDepartmentLabel-{{ $department->id }}">
                    <i class="fa-solid fa-pen-ruler"></i>
                    Edit Department
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('departments.update', $department->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body bg-light py-3 px-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="name-{{ $department->id }}" class="form-label small">Department Name</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="name-{{ $department->id }}" name="name" value="{{ $department->name }}" required>
                        </div>
                        <div class="col-6">
                            <label for="code-{{ $department->id }}" class="form-label small">Department Code</label>
                            <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                                id="code-{{ $department->id }}" name="code" value="{{ $department->code }}"
                                placeholder="Enter department code (optional)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                        data-bs-dismiss="modal">
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
