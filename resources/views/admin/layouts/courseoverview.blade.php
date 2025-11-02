@extends('layouts.admin')

@section('content')
    <div class="card shadow-lg border-0 rounded-4 mt-4">
        <div class="card-header bg-gradient bg-primary text-white rounded-top-4 border-0 py-3 px-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4 class="mb-0 fw-semibold"><i class="fa-solid fa-layer-group me-2"></i>Course Overview (Admin)</h4>

                {{-- Responsive filter controls --}}
                <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                    {{-- Search and filters row --}}
                    <div class="d-flex flex-column flex-sm-row gap-2 flex-grow-1">
                        <input id="searchBox" type="search" class="form-control form-control-sm"
                            placeholder="Search course/code" style="min-width: 200px;">
                        <select id="filterDept" class="form-select form-select-sm" style="min-width: 150px;">
                            <option value="">All Departments</option>
                            @foreach ($departments as $departmentName)
                                <option value="{{ $departmentName }}">{{ $departmentName }}</option>
                            @endforeach
                        </select>
                        <select id="filterYear" class="form-select form-select-sm" style="min-width: 120px;">
                            <option value="">All Years</option>
                            @foreach ($years as $yearName)
                                <option value="{{ $yearName }}">{{ $yearName }}</option>
                            @endforeach
                        </select>
                        <select id="filterType" class="form-select form-select-sm" style="min-width: 120px;">
                            <option value="">All Types</option>
                            @foreach ($types as $typeName)
                                <option value="{{ $typeName }}">{{ $typeName }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action buttons row --}}
                    <div class="d-flex gap-2">
                        <button id="resetFilters" type="button" class="btn btn-outline-light btn-sm">
                            <i class="fa-solid fa-rotate-left me-1"></i><span class="d-none d-sm-inline">Reset</span>
                        </button>
                        <a href="{{ route('courseoffering.selected') }}" class="btn btn-outline-light btn-sm">
                            <i class="fa-solid fa-list-check me-1"></i><span class="d-none d-sm-inline">Selected</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body bg-light rounded-bottom-4 p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            {{-- Summary cards with responsive grid --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3 mb-4" id="summaryRow">
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small">Total Courses</div>
                            <div class="fs-5 fw-semibold" id="sumCourses">{{ $totalCoursesCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small">Total Students (pool)</div>
                            <div class="fs-5 fw-semibold" id="sumStudents">{{ $totalStudentsCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small">Eligible Now</div>
                            <div class="fs-5 fw-semibold" id="sumEligible">{{ $totalEligibleCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small">Prereqs Met</div>
                            <div class="fs-5 fw-semibold" id="sumPrereq">{{ $totalPrereqMetCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                        <div class="card-body py-3">
                            <div class="text-muted small">
                                <i class="fa-solid fa-credit-card me-1"></i>Credits Offered
                            </div>
                            <div class="fs-5 fw-semibold text-primary" id="sumCredits">{{ number_format($totalCreditsOffered ?? 0, 1) }}</div>
                            <div class="text-muted small mt-1">{{ $totalOfferedCourses ?? 0 }} courses</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Responsive meta info --}}
            <div
                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-2 small text-muted gap-1">
                <div id="visibleMeta">Showing <span id="visibleCount">{{ $totalCoursesCount }}</span> of
                    {{ $totalCoursesCount }} courses</div>
                <div class="d-none d-sm-block">Use search and filters to refine.</div>
            </div>

            {{-- Responsive table with horizontal scroll on mobile --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="coursesTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center d-none d-md-table-cell">Course</th>
                            <th class="text-center d-none d-lg-table-cell">Code</th>
                            <th class="text-center d-none d-sm-table-cell">Year</th>
                            <th class="text-center d-none d-lg-table-cell">Dept</th>
                            <th class="text-center d-none d-md-table-cell">Type</th>
                            <th class="text-center">Credit</th>
                            <th class="text-center d-none d-lg-table-cell">Eligible Students</th>
                            <th class="text-center d-none d-xl-table-cell">Prerequisite Completion</th>
                            <th class="text-center d-none d-md-table-cell">Credit Requirement</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($coursesWithPercentages as $course)
                            @php
                                $eligiblePercent = $course['eligible_percent'] ?? 0;
                                $prereqPercent = $course['prereq_percent'] ?? 0;
                                $rowId = $course['rowId'] ?? ($course['id'] ?? ($course['code'] ?? ''));
                            @endphp
                            <tr class="course-row" data-dept="{{ $course['department'] ?? 'N/A' }}"
                                data-year="{{ $course['year'] ?? 'N/A' }}" data-type="{{ $course['type'] ?? 'N/A' }}"
                                data-total="{{ $course['total'] ?? 0 }}" data-eligible="{{ $course['eligible'] ?? 0 }}"
                                data-prereq="{{ $course['prereq_met'] ?? 0 }}"
                                data-credit="{{ $course['credit_hour'] ?? 0 }}"
                                data-students='@json($course['pool_ids'] ?? [])'>
                                {{-- Course name with responsive visibility --}}
                                <td class="fw-semibold text-dark d-none d-md-table-cell">{{ $course['name'] ?? '' }}</td>
                                {{-- Course code badge --}}
                                <td class="text-center d-none d-lg-table-cell">
                                    <span
                                        class="badge bg-info-subtle text-info-emphasis">{{ $course['code'] ?? '' }}</span>
                                </td>
                                {{-- Year --}}
                                <td class="text-center d-none d-sm-table-cell">{{ $course['year'] ?? 'N/A' }}</td>
                                {{-- Department --}}
                                <td class="text-center d-none d-lg-table-cell">{{ $course['department'] ?? 'N/A' }}</td>
                                {{-- Type badge --}}
                                <td class="text-center d-none d-md-table-cell">
                                    <span
                                        class="badge {{ ($course['type'] ?? '') === 'Lab' ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">
                                        {{ $course['type'] ?? 'Theory' }}
                                    </span>
                                </td>
                                {{-- Credit hours (always visible) --}}
                                <td class="text-center">{{ $course['credit_hour'] ?? 0 }}</td>
                                {{-- Eligible students progress --}}
                                <td class="d-none d-lg-table-cell" style="min-width:220px">
                                    <div class="d-flex justify-content-between small">
                                        <span>{{ $course['eligible'] ?? 0 }} / {{ $course['total'] ?? 0 }}</span>
                                        <span>{{ $eligiblePercent }}%</span>
                                    </div>
                                    <div class="progress" style="height:8px">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $eligiblePercent }}%"></div>
                                    </div>
                                </td>
                                {{-- Prerequisite completion progress --}}
                                <td class="d-none d-xl-table-cell" style="min-width:220px">
                                    <div class="d-flex justify-content-between small">
                                        <span>{{ $course['prereq_met'] ?? 0 }} / {{ $course['total'] ?? 0 }}</span>
                                        <span>{{ $prereqPercent }}%</span>
                                    </div>
                                    <div class="progress" style="height:8px">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $prereqPercent }}%"></div>
                                    </div>
                                </td>
                                {{-- Credit requirement --}}
                                <td class="text-center d-none d-md-table-cell">{{ $course['min_credit_req'] ?? 0 }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        {{-- View eligible (placeholder) --}}
                                        <a href="#" class="btn btn-outline-primary" title="View Eligible"
                                            aria-label="View Eligible" data-bs-toggle="modal"
                                            data-bs-target="#eligibleStudentsModal-{{ $rowId }}">
                                            <i class="fa-solid fa-users-viewfinder"></i>
                                        </a>
                                        @include('admin.layouts.modal.eligible_students', [
                                            'course' => $course,
                                        ])
                                        {{-- Offer to Class button --}}
                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#offerToClassModal-{{ $rowId }}"
                                            title="Offer to Class">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        {{-- Drop button (only show if course is offered) --}}
                                        @if ($course['is_offered'] ?? false)
                                            <form method="POST" action="{{ route('courseoverview.drop', $course['id']) }}" 
                                                class="d-inline" 
                                                onsubmit="return confirm('Are you sure you want to drop this course offering? This will remove it from all classes.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                    title="Drop Course Offering">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    {{-- Include modals (id normalized) --}}
                                    @include('admin.layouts.modal.offer_to_class', [
                                        'course' => $course,
                                        'classes' => $classes,
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Responsive legend --}}
            <div class="mt-3 small text-muted">
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <span><i class="fa-solid fa-circle me-1" style="color:#0d6efd"></i>Eligible% = eligible ÷ total</span>
                    <span><i class="fa-solid fa-circle me-1" style="color:#198754"></i>Prereq% = prereq_met ÷ total</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            // Shortcuts
            const $ = (s) => document.querySelector(s);
            const $$ = (s) => Array.from(document.querySelectorAll(s));

            // Controls
            const searchInput = $('#searchBox');
            const departmentFilter = $('#filterDept');
            const yearFilter = $('#filterYear');
            const typeFilter = $('#filterType');
            const resetFiltersBtn = $('#resetFilters');

            // Rows + summary
            const courseRows = $$('.course-row');
            const sumCourses = $('#sumCourses');
            const sumStudents = $('#sumStudents');
            const sumEligible = $('#sumEligible');
            const sumPrereq = $('#sumPrereq');

            // Meta
            const visibleCount = $('#visibleCount');

            // Filter + recalc summaries (keeps sizes intact)
            function applyFiltersAndRecalculate() {
                const term = (searchInput.value || '').toLowerCase();
                const dept = departmentFilter.value;
                const year = yearFilter.value;
                const type = typeFilter.value;

                let visibleCourses = 0;
                let eligibleSum = 0;
                let prereqSum = 0;
                let poolTotalSum = 0;

                const uniquePoolIds = new Set();
                let hasIdData = false;

                courseRows.forEach(row => {
                    const name = row.cells[0].innerText.toLowerCase();
                    const code = row.cells[1].innerText.toLowerCase();

                    const matchesSearch = !term || name.includes(term) || code.includes(term);
                    const matchesDept = !dept || row.dataset.dept === dept;
                    const matchesYear = !year || row.dataset.year === year;
                    const matchesType = !type || row.dataset.type === type;

                    const show = matchesSearch && matchesDept && matchesYear && matchesType;
                    row.style.display = show ? '' : 'none';

                    if (show) {
                        visibleCourses++;
                        eligibleSum += Number(row.dataset.eligible || 0);
                        prereqSum += Number(row.dataset.prereq || 0);
                        poolTotalSum += Number(row.dataset.total || 0);

                        try {
                            const ids = JSON.parse(row.dataset.students || '[]');
                            if (Array.isArray(ids) && ids.length) {
                                hasIdData = true;
                                ids.forEach(id => uniquePoolIds.add(id));
                            }
                        } catch (e) {}
                    }
                });

                sumCourses.textContent = visibleCourses;
                sumStudents.textContent = hasIdData ? uniquePoolIds.size : poolTotalSum;
                sumEligible.textContent = eligibleSum;
                sumPrereq.textContent = prereqSum;

                visibleCount.textContent = visibleCourses;
            }

            // Reset filters (without changing control sizes)
            function resetFilters() {
                searchInput.value = '';
                departmentFilter.value = '';
                yearFilter.value = '';
                typeFilter.value = '';
                applyFiltersAndRecalculate();
            }

            // Bind events
            ['input', 'change'].forEach(ev => {
                searchInput.addEventListener(ev, applyFiltersAndRecalculate);
            });
            departmentFilter.addEventListener('change', applyFiltersAndRecalculate);
            yearFilter.addEventListener('change', applyFiltersAndRecalculate);
            typeFilter.addEventListener('change', applyFiltersAndRecalculate);
            resetFiltersBtn.addEventListener('click', resetFilters);

            // Init
            applyFiltersAndRecalculate();

            // Paint "offered" buttons
            $$('.cof-rules-btn').forEach(btn => {
                const offered = btn.getAttribute('data-offered') === '1';
                const icon = btn.querySelector('i');
                if (offered) {
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-success');
                    if (icon) {
                        icon.classList.remove('fa-gears');
                        icon.classList.add('fa-check-circle', 'text-white');
                    }
                }
            });

            // Optimistic flip after modal submit
            $$('.modal').forEach(modal => {
                const form = modal.querySelector('form');
                if (!form) return;
                form.addEventListener('submit', () => {
                    const subjectIdInput = form.querySelector('input[name="subject_id"]');
                    const subjectId = subjectIdInput ? subjectIdInput.value : null;
                    if (!subjectId) return;
                    const btn = document.getElementById('cof-rules-btn-' + subjectId);
                    if (!btn) return;
                    const icon = btn.querySelector('i');
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-success');
                    btn.setAttribute('data-offered', '1');
                    if (icon) {
                        icon.classList.remove('fa-gears');
                        icon.classList.add('fa-check-circle', 'text-white');
                    }
                });
            });

            // Flash highlight (if any)
            const savedId = {{ (int) (session('saved_subject_id') ?? 0) }};
            if (savedId) {
                const btn = document.getElementById('cof-rules-btn-' + savedId);
                if (btn) {
                    const icon = btn.querySelector('i');
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-success');
                    btn.setAttribute('data-offered', '1');
                    if (icon) {
                        icon.classList.remove('fa-gears');
                        icon.classList.add('fa-check-circle', 'text-white');
                    }
                }
            }
        })();
    </script>
@endsection
