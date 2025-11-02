@extends('layouts.student')

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient bg-primary text-white rounded-top-4 border-0 py-3 px-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h4 class="mb-0 fw-semibold"><i class="fa-solid fa-book me-2"></i>Course Overview</h4>

                        <div class="d-flex align-items-center gap-3">
                            <!-- Credit Limit Display -->
                            <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 rounded px-3 py-2">
                                <i class="fa-solid fa-credit-card"></i>
                                <div class="text-end">
                                    <small class="text-white-50 d-block" style="font-size: 0.75rem;">Current Credits</small>
                                    <strong class="fs-6">{{ $currentSemesterCredits }}/15</strong>
                                </div>
                                @if ($currentSemesterCredits >= 15)
                                    <span class="badge bg-danger ms-2">Limit Reached</span>
                                @elseif ($currentSemesterCredits >= 12)
                                    <span class="badge bg-warning ms-2">{{ 15 - $currentSemesterCredits }} remaining</span>
                                @else
                                    <span class="badge bg-success ms-2">{{ 15 - $currentSemesterCredits }} remaining</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <label for="yearFilter" class="mb-0 small text-white-50">Filter by year</label>
                            <select id="yearFilter" class="form-select form-select-sm w-auto">
                                <option value="All" selected>All</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="Technical Electives">Technical Electives</option>
                            </select>

                            <label for="statusFilter" class="mb-0 small text-white-50">Filter by status</label>
                            <select id="statusFilter" class="form-select form-select-sm w-auto">
                                <option value="All" selected>All</option>
                                <option value="Complete">Complete</option>
                                <option value="Incomplete">Incomplete</option>
                                <option value="Failed">Failed</option>
                            </select>

                            <label for="eligibilityFilter" class="mb-0 small text-white-50">Filter by eligibility</label>
                            <select id="eligibilityFilter" class="form-select form-select-sm w-auto">
                                <option value="All" selected>All</option>
                                <option value="eligible">Eligible</option>
                                <option value="partially_eligible">Partially Eligible</option>
                                <option value="not_eligible">Not Eligible</option>
                            </select>

                            <span class="badge bg-light text-dark px-3 py-2">
                                {{ $student->department->name ?? 'Department' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-light rounded-bottom-4 p-4">
                    @if (isset($error))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>{{ $error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($currentSemesterCredits >= 15)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            <strong>Credit Limit Reached!</strong> You have reached the maximum 15 credit limit for this semester. You cannot enroll in additional courses.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif ($currentSemesterCredits >= 12)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            <strong>Credit Limit Warning!</strong> You have {{ 15 - $currentSemesterCredits }} credits remaining before reaching the 15 credit limit.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($subjects->count() > 0)
                        <div class="row">
                            @foreach (['1st Year', '2nd Year', '3rd Year', '4th Year', 'Technical Electives'] as $year)
                                @php $yearSubjects=$subjects->where('year',$year); @endphp
                                @if ($yearSubjects->count() > 0)
                                    <div class="col-12 mb-4 year-group" data-year="{{ $year }}">
                                        <h5 class="fw-bold text-primary border-bottom border-2 border-primary pb-2 mb-3">
                                            <i class="fa-solid fa-graduation-cap me-2"></i>{{ $year }}
                                        </h5>

                                        <div class="row g-3">
                                            @foreach ($yearSubjects as $subject)
                                                @php
                                                    $subjectStatus = 'Incomplete';
                                                    if ($subject->mark && $subject->mark->isPassingGrade()) {
                                                        $subjectStatus = 'Complete';
                                                    } elseif ($subject->mark) {
                                                        $subjectStatus = 'Failed';
                                                    }

                                                    // Determine border class based on eligibility
                                                    // Only 'eligible' or 'not_eligible' are possible values
                                                    $borderClass = match ($subject->eligibility) {
                                                        'eligible' => 'border-success border-3',
                                                        'not_eligible' => 'border-danger border-3',
                                                        default => 'border-danger border-3', // Default to not eligible for safety
                                                    };

                                                    // Determine eligibility badge - shows correct status based on prerequisites
                                                    $eligibilityBadge = match ($subject->eligibility) {
                                                        'eligible' => 'bg-success text-white',
                                                        'not_eligible' => 'bg-danger text-white',
                                                        default => 'bg-danger text-white', // Default to not eligible for safety
                                                    };

                                                    $eligibilityText = match ($subject->eligibility) {
                                                        'eligible' => 'Eligible',
                                                        'not_eligible' => 'Not Eligible',
                                                        default => 'Not Eligible', // Default to not eligible for safety
                                                    };
                                                @endphp
                                                <div class="col-lg-6 col-md-12 subject-card" data-year="{{ $year }}"
                                                    data-status="{{ $subjectStatus }}"
                                                    data-eligibility="{{ $subject->eligibility }}">
                                                    <div class="card h-100 {{ $borderClass }} shadow-sm hover-shadow">
                                                        <div class="card-body p-3">
                                                            <div
                                                                class="d-flex justify-content-between align-items-start mb-2">
                                                                <h6 class="card-title fw-semibold text-dark mb-1">
                                                                    {{ $subject->name }}</h6>
                                                                <div class="d-flex flex-column align-items-end gap-1">
                                                                    <span
                                                                        class="badge bg-info-subtle text-info-emphasis rounded-pill px-2 py-1">{{ $subject->subject_code }}</span>
                                                                    <span
                                                                        class="badge {{ $eligibilityBadge }} rounded-pill px-2 py-1 small">{{ $eligibilityText }}</span>
                                                                </div>
                                                            </div>

                                                            <div class="row g-2 text-muted small">
                                                                <div class="col-6">
                                                                    <i
                                                                        class="fa-solid fa-user-tie me-1"></i><strong>Teacher:</strong><br>
                                                                    <span
                                                                        class="text-dark">{{ $subject->teacher->name ?? 'Not Assigned' }}</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <i
                                                                        class="fa-solid fa-clock me-1"></i><strong>Credits:</strong><br>
                                                                    <span
                                                                        class="text-dark">{{ $subject->credit->credit_hour ?? 'N/A' }}
                                                                        ({{ ucfirst($subject->credit->subject_type ?? 'N/A') }})
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="row g-2 text-muted small mt-2">
                                                                <div class="col-6">
                                                                    <i
                                                                        class="fa-solid fa-building me-1"></i><strong>Department:</strong><br>
                                                                    <span
                                                                        class="text-dark">{{ $subject->department->name ?? 'N/A' }}</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <i
                                                                        class="fa-solid fa-check-circle me-1"></i><strong>Status:</strong><br>
                                                                    @if ($subject->mark && $subject->mark->isPassingGrade())
                                                                        <span
                                                                            class="badge bg-success-subtle text-success-emphasis rounded-pill px-2 py-1"><i
                                                                                class="fa-solid fa-check me-1"></i>Complete</span>
                                                                    @elseif ($subject->mark)
                                                                        <span
                                                                            class="badge bg-danger-subtle text-danger-emphasis rounded-pill px-2 py-1"><i
                                                                                class="fa-solid fa-times me-1"></i>Failed</span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-danger text-white fw-bold rounded-pill px-2 py-1"><i
                                                                                class="fa-solid fa-clock me-1"></i>Incomplete</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="row g-2 text-muted small mt-2">
                                                                <div class="col-6">
                                                                    <i
                                                                        class="fa-solid fa-chart-line me-1"></i><strong>Marks:</strong><br>
                                                                    @if ($subject->mark)
                                                                        @php
                                                                            $markValue = $subject->mark->marks;
                                                                            $maxMarks = 100; // Standard max marks
                                                                        @endphp
                                                                        @if ($markValue >= 80)
                                                                            <span
                                                                                class="text-success fw-semibold">{{ $markValue }}/{{ $maxMarks }}</span>
                                                                        @elseif ($markValue >= 60)
                                                                            <span
                                                                                class="text-info fw-semibold">{{ $markValue }}/{{ $maxMarks }}</span>
                                                                        @elseif ($markValue >= 50)
                                                                            <span
                                                                                class="text-warning fw-semibold">{{ $markValue }}/{{ $maxMarks }}</span>
                                                                        @elseif ($markValue >= 40)
                                                                            <span
                                                                                class="text-danger fw-semibold">{{ $markValue }}/{{ $maxMarks }}</span>
                                                                        @else
                                                                            <span
                                                                                class="text-danger fw-semibold">{{ $markValue }}/{{ $maxMarks }}</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="text-muted fw-semibold">Not
                                                                            Graded</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-6">
                                                                    <i
                                                                        class="fa-solid fa-star me-1"></i><strong>Grade:</strong><br>
                                                                    @if ($subject->grade)
                                                                        @php
                                                                            $grade = $subject->grade->grade;
                                                                            $gradeClass = match ($grade) {
                                                                                'A+'
                                                                                    => 'bg-success-subtle text-success-emphasis',
                                                                                'A'
                                                                                    => 'bg-success-subtle text-success-emphasis',
                                                                                'B'
                                                                                    => 'bg-info-subtle text-info-emphasis',
                                                                                'C'
                                                                                    => 'bg-warning-subtle text-warning-emphasis',
                                                                                'D'
                                                                                    => 'bg-danger-subtle text-danger-emphasis',
                                                                                'F'
                                                                                    => 'bg-danger-subtle text-danger-emphasis',
                                                                                default
                                                                                    => 'bg-secondary-subtle text-secondary-emphasis',
                                                                            };
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $gradeClass }} rounded-pill px-2 py-1">{{ $grade }}</span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill px-2 py-1">N/A</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Prerequisites Information -->
                                                            @if ($subject->prerequisites->isNotEmpty())
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col-12">
                                                                        <i class="fa-solid fa-list-check me-1 text-muted"></i><strong class="text-muted small">Prerequisites:</strong>
                                                                        <div class="mt-1">
                                                                            @php
                                                                                // Get all subjects student has PASSED (earned credits)
                                                                                $passedSubjectIds = $student->getPassedSubjectIds();
                                                                                $completedCount = 0;
                                                                                $totalPrereqs = $subject->prerequisites->count();
                                                                            @endphp
                                                                            @foreach ($subject->prerequisites as $prereq)
                                                                                @php
                                                                                    // Check if student has PASSED this prerequisite (earned credits)
                                                                                    $isCompleted = in_array($prereq->id, $passedSubjectIds);
                                                                                    if ($isCompleted) {
                                                                                        $completedCount++;
                                                                                    }
                                                                                @endphp
                                                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                                                    @if ($isCompleted)
                                                                                        <i class="fa-solid fa-check-circle text-success small"></i>
                                                                                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-2 py-1 small">
                                                                                            {{ $prereq->name }} ({{ $prereq->subject_code }})
                                                                                        </span>
                                                                                    @else
                                                                                        <i class="fa-solid fa-times-circle text-danger small"></i>
                                                                                        <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill px-2 py-1 small">
                                                                                            {{ $prereq->name }} ({{ $prereq->subject_code }})
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                            @if ($subject->eligibility !== 'eligible')
                                                                                <small class="text-danger mt-1 d-block">
                                                                                    <i class="fa-solid fa-info-circle me-1"></i>
                                                                                    Pass {{ $completedCount }}/{{ $totalPrereqs }} prerequisites to enroll
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="mt-2">
                                                                    <small class="text-muted">
                                                                        <i class="fa-solid fa-info-circle me-1"></i>No prerequisites required
                                                                    </small>
                                                                </div>
                                                            @endif

                                                            <!-- Individual Enroll Button -->
                                                            @php
                                                                $courseCredits = $subject->credit ? (float) $subject->credit->credit_hour : 0;
                                                                $wouldExceedLimit = ($currentSemesterCredits + $courseCredits) > 15;
                                                                
                                                                // Check all conditions for enrollment:
                                                                // 1. Prerequisites must be earned (passed)
                                                                $passedSubjectIds = $student->getPassedSubjectIds();
                                                                $prerequisitesEarned = true;
                                                                if ($subject->prerequisites->isNotEmpty()) {
                                                                    foreach ($subject->prerequisites as $prereq) {
                                                                        if (!in_array($prereq->id, $passedSubjectIds)) {
                                                                            $prerequisitesEarned = false;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                
                                                                // 2. Credit requirement must be met
                                                                $minCreditsRequired = 0;
                                                                if ($subject->prerequisites->isNotEmpty()) {
                                                                    $minCreditsRequired = $subject->prerequisites->max(function ($prereq) {
                                                                        return (int) ($prereq->pivot->required_credits ?? 0);
                                                                    });
                                                                }
                                                                $creditsEarned = ($minCreditsRequired === 0) || ($student->credit_completed >= $minCreditsRequired);
                                                                
                                                                // 3. Course must be offered by admin
                                                                $courseOffered = ($subject->is_offered ?? false);
                                                                
                                                                // 4. Course must be eligible (combines prerequisite and credit checks)
                                                                $courseEligible = ($subject->eligibility === 'eligible');
                                                                
                                                                // All conditions must be true for enroll button to appear
                                                                $canEnroll = $prerequisitesEarned && $creditsEarned && $courseOffered && $courseEligible && !$wouldExceedLimit;
                                                            @endphp
                                                            
                                                            @if ($canEnroll)
                                                                <div class="mt-3 text-end">
                                                                    <form action="{{ route('student.enroll.course', $subject->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-primary btn-sm"
                                                                            onclick="return confirm('Are you sure you want to enroll in {{ $subject->name }}?');">
                                                                            <i class="fa-solid fa-plus me-1"></i>Enroll
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @else
                                                                <div class="mt-3">
                                                                    @if ($wouldExceedLimit && $prerequisitesEarned && $creditsEarned && $courseOffered && $courseEligible)
                                                                        <small class="text-danger fw-semibold">
                                                                            <i class="fa-solid fa-exclamation-triangle me-1"></i>
                                                                            Cannot enroll - Would exceed 15 credit limit ({{ $currentSemesterCredits }}/15 + {{ $courseCredits }} = {{ $currentSemesterCredits + $courseCredits }})
                                                                        </small>
                                                                    @elseif (!$courseOffered)
                                                                        <small class="text-warning fw-semibold">
                                                                            <i class="fa-solid fa-clock me-1"></i>
                                                                            Course not offered by admin
                                                                        </small>
                                                                    @elseif (!$prerequisitesEarned)
                                                                        <small class="text-danger fw-semibold">
                                                                            <i class="fa-solid fa-lock me-1"></i>
                                                                            Cannot enroll - Prerequisites not earned. Pass all prerequisite courses to enroll.
                                                                        </small>
                                                                    @elseif (!$creditsEarned)
                                                                        <small class="text-danger fw-semibold">
                                                                            <i class="fa-solid fa-credit-card me-1"></i>
                                                                            Cannot enroll - Credit requirement not met (Need {{ $minCreditsRequired }} credits, have {{ $student->credit_completed }})
                                                                        </small>
                                                                    @elseif (!$courseEligible)
                                                                        <small class="text-danger fw-semibold">
                                                                            <i class="fa-solid fa-ban me-1"></i>
                                                                            Course is not eligible for enrollment
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info border-0 rounded-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-info-circle me-3 fs-4"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">Course Information</h6>
                                            <p class="mb-0">This overview shows all available courses for your
                                                department.
                                                You can enroll in these courses through the subject enrollment section.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-book-open fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No courses available</h5>
                            <p class="text-muted">There are currently no courses available for your department.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function applyFilters() {
            const yearFilter = document.getElementById('yearFilter')?.value || 'All';
            const statusFilter = document.getElementById('statusFilter')?.value || 'All';
            const eligibilityFilter = document.getElementById('eligibilityFilter')?.value || 'All';

            // Filter year groups
            document.querySelectorAll('.year-group').forEach(yearGroup => {
                const yearMatch = yearFilter === 'All' || yearGroup.dataset.year === yearFilter;
                yearGroup.style.display = yearMatch ? 'block' : 'none';
            });

            // Filter subject cards within visible year groups
            document.querySelectorAll('.subject-card').forEach(card => {
                const yearMatch = yearFilter === 'All' || card.dataset.year === yearFilter;
                const statusMatch = statusFilter === 'All' || card.dataset.status === statusFilter;
                const eligibilityMatch = eligibilityFilter === 'All' || card.dataset.eligibility ===
                    eligibilityFilter;

                if (yearMatch && statusMatch && eligibilityMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Hide year groups that have no visible subjects
            document.querySelectorAll('.year-group').forEach(yearGroup => {
                const visibleSubjects = yearGroup.querySelectorAll(
                    '.subject-card[style*="block"], .subject-card:not([style*="none"])');
                if (visibleSubjects.length === 0) {
                    yearGroup.style.display = 'none';
                }
            });
        }

        // Add event listeners
        document.getElementById('yearFilter')?.addEventListener('change', applyFilters);
        document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
        document.getElementById('eligibilityFilter')?.addEventListener('change', applyFilters);

        // Apply filters on page load
        document.addEventListener('DOMContentLoaded', applyFilters);
    </script>
@endsection
