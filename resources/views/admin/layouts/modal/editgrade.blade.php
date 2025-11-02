<!-- resources/views/admin/layouts/modal/editgrade.blade.php -->
<div class="modal fade"
     id="editGradeModal-{{ $grade->id }}"
     tabindex="-1"
     aria-labelledby="editGradeModalLabel-{{ $grade->id }}"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-dark bg-gradient text-white rounded-top-4">
                <h5 class="modal-title d-flex align-items-center gap-2" id="editGradeModalLabel-{{ $grade->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit Grade
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('grades.update', $grade->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <table class="table mb-0 align-middle" style="border: none;">
                            <tbody>
                                <tr>
                                    <th class="text-end align-middle" style="width: 28%; border: none;">
                                        <label for="edit_min_marks_{{ $grade->id }}" class="form-label mb-0">Minimum Marks</label>
                                    </th>
                                    <td style="border: none;">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="fa-solid fa-arrow-down-1-9"></i>
                                            </span>
                                            <input type="number" class="form-control shadow-sm rounded-3"
                                                id="edit_min_marks_{{ $grade->id }}"
                                                name="min_marks"
                                                min="0" max="100"
                                                value="{{ old('min_marks', $grade->min_marks) }}"
                                                required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end align-middle" style="border: none;">
                                        <label for="edit_max_marks_{{ $grade->id }}" class="form-label mb-0">Maximum Marks</label>
                                    </th>
                                    <td style="border: none;">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="fa-solid fa-arrow-up-1-9"></i>
                                            </span>
                                            <input type="number" class="form-control shadow-sm rounded-3"
                                                id="edit_max_marks_{{ $grade->id }}"
                                                name="max_marks"
                                                min="0" max="100"
                                                value="{{ old('max_marks', $grade->max_marks) }}"
                                                required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end align-middle" style="border: none;">
                                        <label for="edit_grade_{{ $grade->id }}" class="form-label mb-0">Grade</label>
                                    </th>
                                    <td style="border: none;">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="fa-solid fa-font"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm rounded-3"
                                                id="edit_grade_{{ $grade->id }}"
                                                name="grade"
                                                maxlength="2"
                                                value="{{ old('grade', $grade->grade) }}"
                                                required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end align-middle" style="border: none;">
                                        <label for="edit_remarks_{{ $grade->id }}" class="form-label mb-0">Remarks</label>
                                        <span class="fw-light text-muted small">(Optional)</span>
                                    </th>
                                    <td style="border: none;">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="fa-solid fa-comment"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm rounded-3"
                                                id="edit_remarks_{{ $grade->id }}"
                                                name="remarks"
                                                value="{{ old('remarks', $grade->remarks) }}">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
