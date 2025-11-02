<!-- resources/views/admin/layouts/modal/createcourseoffering.blade.php -->
@if (isset($course))
    <div class="modal fade" id="createCourseOffering-{{ $course['id'] }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 shadow border-0">
                <div class="modal-header bg-dark text-white rounded-top-4 border-0">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="fa-solid fa-gears"></i>
                        <span id="cof-title-{{ $course['id'] }}">Course Offering Rules</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-3">
                    <!-- Main Form View -->
                    <div id="cof-main-{{ $course['id'] }}">
                        <form method="POST" action="{{ route('courseoverview.store') }}">
                            @csrf
                            <input type="hidden" name="subject_id" value="{{ $course['id'] }}">
                            <input type="hidden" name="credit_hour" value="{{ $course['credit_hour'] ?? '' }}">
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control"
                                    value="{{ $course['name'] ?? '' }} ({{ $course['code'] ?? '' }})" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit</label>
                                <input type="text" class="form-control" value="{{ $course['credit_hour'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Requirement</label>
                                <input type="text" class="form-control" value="{{ $course['min_credit_req'] ?? 0 }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Class</label>
                                <select name="class_id" class="form-select">
                                    <option value="">Select class (optional)</option>
                                    @foreach ($classes ?? [] as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="enforce_prereq" value="1"
                                    id="autoPrereq-{{ $course['id'] }}" checked>
                                <label class="form-check-label" for="autoPrereq-{{ $course['id'] }}">
                                    Automatically enforce prerequisite completion
                                </label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-info"
                                    id="cof-show-prev-{{ $course['id'] }}">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i>Previously Selected Subjects
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-1"></i>Submit
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Previous Selections View -->
                    <div id="cof-prev-{{ $course['id'] }}" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-list-check"></i> Previously Selected Subjects
                            </h6>
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                id="cof-back-{{ $course['id'] }}">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Credit</th>
                                        <th>Rule</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $course['name'] ?? '' }}</td>
                                        <td>{{ $course['credit_hour'] ?? '' }}</td>
                                        <td>
                                            Prereq enforced
                                            @if (($course['min_credit_req'] ?? 0) > 0)
                                                · Credit threshold {{ $course['min_credit_req'] }}+
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Sample Subject (Static)</td>
                                        <td>3</td>
                                        <td>Credit threshold 18+</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainView{{ $course['id'] }} = document.getElementById('cof-main-{{ $course['id'] }}');
            const prevView{{ $course['id'] }} = document.getElementById('cof-prev-{{ $course['id'] }}');
            const showPrev{{ $course['id'] }} = document.getElementById('cof-show-prev-{{ $course['id'] }}');
            const backBtn{{ $course['id'] }} = document.getElementById('cof-back-{{ $course['id'] }}');
            const title{{ $course['id'] }} = document.getElementById('cof-title-{{ $course['id'] }}');

            if (showPrev{{ $course['id'] }}) {
                showPrev{{ $course['id'] }}.addEventListener('click', function() {
                    mainView{{ $course['id'] }}.classList.add('d-none');
                    prevView{{ $course['id'] }}.classList.remove('d-none');
                    if (title{{ $course['id'] }}) title{{ $course['id'] }}.textContent =
                        'Previously Selected Subjects';
                });
            }
            if (backBtn{{ $course['id'] }}) {
                backBtn{{ $course['id'] }}.addEventListener('click', function() {
                    prevView{{ $course['id'] }}.classList.add('d-none');
                    mainView{{ $course['id'] }}.classList.remove('d-none');
                    if (title{{ $course['id'] }}) title{{ $course['id'] }}.textContent =
                        'Course Offering Rules';
                });
            }

            // Reset view on modal hide
            const modalEl = document.getElementById('createCourseOffering-{{ $course['id'] }}');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function() {
                    prevView{{ $course['id'] }}.classList.add('d-none');
                    mainView{{ $course['id'] }}.classList.remove('d-none');
                    if (title{{ $course['id'] }}) title{{ $course['id'] }}.textContent =
                        'Course Offering Rules';
                });
            }
        });
    </script>
@endif
