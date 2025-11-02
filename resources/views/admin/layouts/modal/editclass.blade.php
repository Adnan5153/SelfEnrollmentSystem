<!-- resources/views/admin/layouts/modal/editclass.blade.php -->
<div class="modal fade" id="editClassModal-{{ $class->id }}" tabindex="-1" aria-labelledby="editClassLabel-{{ $class->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                <h6 class="modal-title d-flex align-items-center gap-2 mb-0" id="editClassLabel-{{ $class->id }}">
                    <i class="fa-solid fa-pen-ruler"></i>
                    Edit Class
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('classes.update', $class->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body bg-light py-3 px-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="class_name-{{ $class->id }}" class="form-label small">Class Name</label>
                            <input type="text"
                                   class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="class_name-{{ $class->id }}"
                                   name="class_name"
                                   value="{{ $class->class_name }}"
                                   required>
                        </div>
                        <div class="col-3">
                            <label for="section-{{ $class->id }}" class="form-label small">Section</label>
                            <input type="text"
                                   class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="section-{{ $class->id }}"
                                   name="section"
                                   value="{{ $class->section }}"
                                   required>
                        </div>
                        <div class="col-3">
                            <label for="capacity-{{ $class->id }}" class="form-label small">Capacity</label>
                            <input type="number"
                                   class="form-control form-control-sm rounded-3 shadow-sm"
                                   id="capacity-{{ $class->id }}"
                                   name="capacity"
                                   min="1"
                                   value="{{ $class->capacity }}"
                                   required>
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
