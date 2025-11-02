<!-- resources/views/admin/layouts/modal/editcreditperyear.blade.php -->
@if (isset($row) && isset($departments) && isset($years))
    <div class="modal fade" id="editCreditReq-{{ $row->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0">
                <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                    <h6 class="modal-title d-flex align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit Credit Requirement
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('creditperyear.update', $row->id) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body bg-light py-3 px-3">
                        <div class="mb-2">
                            <label class="form-label small">Department</label>
                            <select name="department_id" class="form-select form-select-sm rounded-3 shadow-sm"
                                required>
                                @foreach ($departments as $d)
                                    <option value="{{ $d->id }}"
                                        {{ $row->department_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }} ({{ $d->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Year</label>
                            <select name="year" class="form-select form-select-sm rounded-3 shadow-sm" required>
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" {{ $row->year === $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Required Credits</label>
                            <input type="number" name="required_credits"
                                class="form-control form-control-sm rounded-3 shadow-sm" min="0" step="0.1"
                                value="{{ old('required_credits', $row->required_credits) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
