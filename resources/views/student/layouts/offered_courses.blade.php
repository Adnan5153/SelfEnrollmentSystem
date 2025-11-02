@extends('layouts.student')

@section('content')
    <div class="container-lg py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fa-solid fa-layer-group me-2 text-success"></i>
                Offered Courses & Prerequisites
            </h3>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <small class="text-muted">Current Credits:</small>
                    <div class="fw-bold text-primary fs-5">{{ $currentSemesterCredits }}/15</div>
                </div>
                <div class="progress" style="width: 100px; height: 8px;">
                    <div class="progress-bar {{ $currentSemesterCredits >= 15 ? 'bg-danger' : ($currentSemesterCredits >= 12 ? 'bg-warning' : 'bg-success') }}"
                        role="progressbar" style="width: {{ min(($currentSemesterCredits / 15) * 100, 100) }}%">
                    </div>
                </div>
            </div>
        </div>
        @if ($subjects->isEmpty())
            <div class="alert alert-warning shadow-sm rounded-3 border-0">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                No offered courses are available.
            </div>
        @else
            @if ($currentSemesterCredits >= 15)
                <div class="alert alert-danger shadow-sm rounded-3 border-0">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <strong>Credit Limit Reached!</strong> You have already reached the maximum 15 credit limit for this
                    semester.
                </div>
            @elseif ($currentSemesterCredits >= 12)
                <div class="alert alert-warning shadow-sm rounded-3 border-0">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <strong>Credit Limit Warning!</strong> You have {{ 15 - $currentSemesterCredits }} credits remaining
                    before reaching the 15 credit limit.
                </div>
            @endif
            <form action="{{ route('student.enroll.courses.bulk') }}" method="POST" id="course-enroll-form">
                @csrf
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-gradient bg-dark text-white rounded-top-4 border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-layer-group me-2"></i> Offered Courses
                        </h5>
                    </div>
                    <div class="card-body bg-light rounded-bottom-4">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0 rounded-4 overflow-hidden">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-center" style="width:4%">#</th>
                                        <th>Course Name</th>
                                        <th>Type</th>
                                        <th>Credits</th>
                                        <th>Prerequisites</th>
                                        <th>Eligibility</th>
                                        <th>Timing</th>
                                        <th>Teacher</th>
                                        <th>Room</th>
                                        <th>Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjects as $subject)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="fw-medium align-middle">{{ $subject->name }}</td>
                                            <td class="align-middle">
                                                @if ($subject->credit)
                                                    <span
                                                        class="badge rounded-pill
                                                        {{ $subject->credit->subject_type === 'theory' ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                                                        {{ ucfirst($subject->credit->subject_type) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-1">
                                                    {{ $subject->credit_hours }}
                                                </span>
                                            </td>
                                            {{-- Prerequisites --}}
                                            <td class="align-middle">
                                                @if ($subject->prerequisites->isEmpty())
                                                    <span class="text-muted">None</span>
                                                @else
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach ($subject->prerequisites as $prereq)
                                                            <span class="badge bg-light border text-dark fw-bold mb-1">
                                                                {{ $prereq->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            {{-- Eligibility --}}
                                            <td class="align-middle">
                                                @if ($subject->is_eligible)
                                                    <span class="badge bg-success text-white rounded-pill px-2 py-1">
                                                        Eligible
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1">
                                                        Not Eligible
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Timing --}}
                                            <td class="align-middle">
                                                @if ($subject->class_routines->isEmpty())
                                                    <span class="text-muted">Not Scheduled</span>
                                                @else
                                                    @foreach ($subject->class_routines as $routine)
                                                        <div class="mb-1">
                                                            <strong>{{ $routine->day_of_week }}</strong>:
                                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                            {{-- Teacher --}}
                                            <td class="align-middle">
                                                @if ($subject->class_routines->isEmpty())
                                                    <span class="text-muted">N/A</span>
                                                @else
                                                    @foreach ($subject->class_routines as $routine)
                                                        <div class="mb-1">
                                                            {{ $routine->teacher->name ?? 'No Teacher Assigned' }}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                            {{-- Room --}}
                                            <td class="align-middle">
                                                @if ($subject->class_routines->isEmpty())
                                                    <span class="text-muted">N/A</span>
                                                @else
                                                    @foreach ($subject->class_routines as $routine)
                                                        <div class="mb-1">
                                                            {{ $routine->room_number ?? 'N/A' }}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                            {{-- Select --}}
                                            <td class="align-middle text-center">
                                                @php
                                                    $isConfirmed = in_array($subject->id, $enrolledSubjectIds ?? []);
                                                @endphp
                                                <button type="button"
                                                    class="select-course-btn btn {{ $isConfirmed ? 'btn-success course-selected' : ($subject->is_eligible ? 'btn-outline-success' : 'btn-outline-secondary') }} btn-sm rounded-circle p-1 shadow-sm"
                                                    style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center;"
                                                    data-course-id="{{ $subject->id }}"
                                                    data-confirmed="{{ $isConfirmed ? '1' : '0' }}"
                                                    title="{{ $isConfirmed ? 'Already Enrolled' : ($subject->is_eligible ? 'Select Course' : 'Not Eligible - Complete Prerequisites') }}"
                                                    {{ !$subject->is_eligible && !$isConfirmed ? 'disabled' : '' }}>
                                                    <i
                                                        class="fa-solid {{ $isConfirmed ? 'fa-check' : ($subject->is_eligible ? 'fa-plus' : 'fa-lock') }}"></i>
                                                </button>
                                                <!-- Hidden checkbox to submit selected IDs (only for not yet confirmed) -->
                                                <input type="checkbox" name="selected_courses[]"
                                                    value="{{ $subject->id }}" class="d-none course-checkbox"
                                                    {{ $isConfirmed ? 'checked disabled' : '' }} />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit"
                                class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-2 px-3 py-1 shadow-sm"
                                style="font-weight: 500; font-size: 1rem; letter-spacing: .03em;">
                                <i class="fa-solid fa-check"></i>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @include('student.modal.course_enroll_preview')
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .select-course-btn:disabled {
            cursor: not-allowed !important;
            opacity: 0.8 !important;
            pointer-events: none;
        }

        .select-course-btn:disabled:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        .course-selected {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
        }

        .row-selected {
            background-color: #f8f9fa !important;
            border-left: 4px solid #28a745 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.querySelector('.table');
            if (!table) return;

            // Credit limit constants
            const MAX_CREDITS = 15;
            const currentCredits = {{ $currentSemesterCredits }};
            let selectedCredits = 0;

            // Update credit display
            function updateCreditDisplay() {
                const creditDisplay = document.querySelector('.fw-bold.text-primary.fs-5');
                const progressBar = document.querySelector('.progress-bar');
                let extraCredits = 0;
                document.querySelectorAll('.course-checkbox:not([disabled]):checked').forEach(cb => {
                    const row = cb.closest('tr');
                    const creditsCell = row.querySelector('td:nth-child(4) .badge');
                    const courseCredits = parseFloat(creditsCell.textContent) || 0;
                    extraCredits += courseCredits;
                });
                const totalCredits = currentCredits + extraCredits;

                if (creditDisplay) {
                    creditDisplay.textContent = `${totalCredits}/15`;
                }
                if (progressBar) {
                    const percentage = Math.min((totalCredits / MAX_CREDITS) * 100, 100);
                    progressBar.style.width = `${percentage}%`;
                    progressBar.className = 'progress-bar';
                    if (totalCredits >= MAX_CREDITS) {
                        progressBar.classList.add('bg-danger');
                    } else if (totalCredits >= 12) {
                        progressBar.classList.add('bg-warning');
                    } else {
                        progressBar.classList.add('bg-success');
                    }
                }
            }

            // Check if adding a course would exceed credit limit
            function canAddCourse(credits) {
                return (currentCredits + selectedCredits + credits) <= MAX_CREDITS;
            }

            // Course selection button (toggle only if not confirmed)
            table.querySelectorAll('.select-course-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    const isConfirmed = btn.getAttribute('data-confirmed') === '1';
                    if (isConfirmed) {
                        // Already confirmed/enrolled: do not allow changes
                        return;
                    }
                    const parentTd = btn.closest('td');
                    const checkbox = parentTd.querySelector('.course-checkbox');
                    const row = btn.closest('tr');
                    const creditsCell = row.querySelector('td:nth-child(4) .badge');
                    const courseCredits = parseFloat(creditsCell.textContent) || 0;

                    // If currently selected -> deselect (allowed only before confirmation)
                    if (checkbox.checked) {
                        checkbox.checked = false;
                        selectedCredits = Math.max(0, selectedCredits - courseCredits);

                        // Revert button state
                        btn.classList.remove('btn-success', 'course-selected');
                        btn.classList.add('btn-outline-success');
                        btn.innerHTML = '<i class="fa-solid fa-plus"></i>';
                        btn.title = 'Select Course';
                        btn.style.cursor = '';
                        btn.style.opacity = '';

                        // Remove row highlight
                        row.classList.remove('row-selected');

                        updateCreditDisplay();
                        return;
                    }

                    // From here: trying to select
                    // If button is disabled (ineligible), do nothing
                    if (btn.disabled) {
                        return;
                    }

                    // Check eligibility
                    const eligibilityBadge = row.querySelector('td:nth-child(6) .badge');
                    const isEligible = eligibilityBadge && eligibilityBadge.classList.contains(
                        'bg-success');
                    if (!isEligible) {
                        alert(
                            'You are not eligible to take this course. Please complete the prerequisites first.'
                        );
                        return;
                    }

                    // Credit limit check
                    if (!canAddCourse(courseCredits)) {
                        alert(
                            `Cannot add this course. Adding ${courseCredits} credits would exceed the 15 credit limit.`
                        );
                        return;
                    }

                    // Select the course
                    checkbox.checked = true;
                    selectedCredits += courseCredits;

                    // Update button to selected state (do NOT disable; allow toggle)
                    btn.classList.remove('btn-outline-success');
                    btn.classList.add('btn-success', 'course-selected');
                    btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                    btn.title = 'Deselect Course';

                    // Highlight the table row
                    row.classList.add('row-selected');

                    updateCreditDisplay();
                });
            });

            // Intercept form submit for preview modal
            const enrollForm = document.getElementById('course-enroll-form');
            const previewModal = new bootstrap.Modal(document.getElementById('courseEnrollPreviewModal'));

            enrollForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Gather selected courses
                const selectedCheckboxes = enrollForm.querySelectorAll('.course-checkbox:checked');
                if (selectedCheckboxes.length === 0) {
                    alert('Please select at least one course!');
                    return;
                }

                // Validate credit limit
                let totalSelectedCredits = 0;
                selectedCheckboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const creditsCell = row.querySelector('td:nth-child(4) .badge');
                    const courseCredits = parseFloat(creditsCell.textContent) || 0;
                    totalSelectedCredits += courseCredits;
                });

                if (currentCredits + totalSelectedCredits > MAX_CREDITS) {
                    alert(
                        `Cannot enroll in selected courses. Total credits (${currentCredits + totalSelectedCredits}) would exceed the 15 credit limit.`
                    );
                    return;
                }

                // Prepare data for modal
                let tbodyHtml = '';
                let totalCredits = 0;
                selectedCheckboxes.forEach((checkbox, idx) => {
                    // Get row elements
                    const tr = checkbox.closest('tr');
                    const name = tr.querySelector('td.fw-medium').textContent.trim();
                    const timings = Array.from(tr.querySelectorAll('td:nth-child(7) div'))
                        .map(div => div.textContent.trim()).join('<br>') || tr.querySelector(
                            'td:nth-child(7)').textContent.trim();
                    const teachers = Array.from(tr.querySelectorAll('td:nth-child(8) div'))
                        .map(div => div.textContent.trim()).join('<br>') || tr.querySelector(
                            'td:nth-child(8)').textContent.trim();
                    // Credits
                    const creditsCell = tr.querySelector('td:nth-child(4) .badge');
                    const credits = parseFloat(creditsCell.textContent) || 0;
                    totalCredits += credits;

                    tbodyHtml += `<tr>
                <td>${idx + 1}</td>
                <td>${name}</td>
                <td>${timings}</td>
                <td>${teachers}</td>
                <td>${credits > 0 ? credits : '-'}</td>
            </tr>`;
                });
                document.getElementById('preview-course-table-body').innerHTML = tbodyHtml;
                document.getElementById('preview-course-total-credits').textContent = totalCredits;

                // Show modal
                previewModal.show();
            });

            // Final submit from modal
            document.getElementById('enroll-final-submit-btn').addEventListener('click', function() {
                enrollForm.submit();
            });
        });
    </script>
@endpush
