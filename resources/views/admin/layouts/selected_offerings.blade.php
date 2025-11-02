@extends('layouts.admin')

@section('content')
    <div class="card shadow-lg border-0 rounded-4 mt-4">
        <div
            class="card-header bg-dark text-white rounded-top-4 border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold"><i class="fa-solid fa-list-check me-2"></i>Selected Subjects</h4>
            <a href="{{ route('courseoverview.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Overview
            </a>
        </div>
        <div class="card-body bg-light rounded-bottom-4 p-4">
            @php
                $years = collect($offerings ?? [])
                    ->map(fn($o) => $o->subject?->year)
                    ->filter()
                    ->unique()
                    ->values();
                $classes = collect($offerings ?? [])
                    ->map(fn($o) => $o->class?->class_name)
                    ->filter()
                    ->unique()
                    ->values();
            @endphp
            
            <!-- Credit Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body bg-primary bg-opacity-10">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted d-block mb-1">Total Credits Offered</small>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalCredits ?? 0, 1) }}</h3>
                                </div>
                                <i class="fa-solid fa-credit-card fa-2x text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body bg-success bg-opacity-10">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted d-block mb-1">Total Courses</small>
                                    <h3 class="mb-0 fw-bold text-success">{{ count($offerings ?? []) }}</h3>
                                </div>
                                <i class="fa-solid fa-book fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body bg-info bg-opacity-10">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted d-block mb-1">Classes with Offerings</small>
                                    <h3 class="mb-0 fw-bold text-info">{{ count($classes ?? []) }}</h3>
                                </div>
                                <i class="fa-solid fa-chalkboard-user fa-2x text-info opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credits by Class -->
            @if (isset($creditsByClass) && $creditsByClass->isNotEmpty())
                <div class="alert alert-info border-0 rounded-3 mb-3">
                    <h6 class="alert-heading mb-3">
                        <i class="fa-solid fa-chart-pie me-2"></i>Credits Offered by Class
                    </h6>
                    <div class="row g-2">
                        @foreach ($creditsByClass as $classId => $credits)
                            @php
                                // Get class name from offerings collection
                                $classOffering = collect($offerings)->firstWhere('class_id', $classId);
                                $className = $classOffering?->class?->class_name ?? 'Unknown Class';
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                    <span class="fw-semibold">{{ $className }}</span>
                                    <span class="badge bg-primary">{{ number_format($credits, 1) }} Credits</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <select id="filterYear" class="form-select form-select-sm w-auto">
                    <option value="">All Years</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <select id="filterType" class="form-select form-select-sm w-auto">
                    <option value="">All Types</option>
                    <option value="theory">Theory</option>
                    <option value="lab">Lab</option>
                </select>
                <select id="filterClass" class="form-select form-select-sm w-auto">
                    <option value="">All Classes</option>
                    @foreach ($classes as $className)
                        <option value="{{ $className }}">{{ $className }}</option>
                    @endforeach
                </select>
                <form method="POST" action="{{ route('courseoffering.selected.clear') }}" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to delete ALL selected offerings? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fa-solid fa-trash-can me-1"></i>Reset All
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Year</th>
                            <th>Credit</th>
                            <th>Prereq Enforced</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white" id="offeringsBody">
                        @forelse($offerings as $index => $offering)
                            @php
                                $rowCredit =
                                    (float) ($offering->credit_hour ??
                                        (optional(optional($offering->subject)->credit)->credit_hour ?? 0));
                                $rowType = strtolower($offering->subject?->credit?->subject_type ?? '');
                                $rowYear = $offering->subject?->year ?? '';
                                $rowClass = $offering->class?->class_name ?? '';
                            @endphp
                            <tr data-year="{{ $rowYear }}" data-type="{{ $rowType }}"
                                data-class="{{ $rowClass }}" data-credit="{{ $rowCredit }}"
                                data-subject-id="{{ $offering->subject_id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $offering->class?->class_name ?? '—' }}</td>
                                <td>{{ $offering->subject?->name }} ({{ $offering->subject?->subject_code }})</td>
                                <td>{{ $offering->subject?->year ?? 'N/A' }}</td>
                                <td>{{ $offering->credit_hour ?? $offering->subject?->credit?->credit_hour }}</td>
                                <td>
                                    <span
                                        class="badge {{ $offering->enforce_prereq ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">
                                        {{ $offering->enforce_prereq ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editOffering-{{ $offering->id }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form method="POST"
                                            action="{{ route('courseoffering.selected.delete', $offering->id) }}"
                                            class="d-inline" onsubmit="return confirm('Delete this offering?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editOffering-{{ $offering->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content rounded-4 shadow border-0">
                                                <div class="modal-header bg-dark text-white rounded-top-4 border-0">
                                                    <h5 class="modal-title d-flex align-items-center gap-2">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        Edit Offering
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('courseoffering.selected.update', $offering->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body bg-light p-3">
                                                        <div class="mb-3">
                                                            <label class="form-label">Subject</label>
                                                            <select name="subject_id" class="form-select">
                                                                @foreach ($subjects ?? [] as $subject)
                                                                    <option value="{{ $subject->id }}"
                                                                        {{ $subject->id === $offering->subject_id ? 'selected' : '' }}>
                                                                        {{ $subject->name }}
                                                                        ({{ $subject->subject_code }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Credit</label>
                                                            <input type="text" name="credit_hour" class="form-control"
                                                                value="{{ $offering->credit_hour ?? $offering->subject?->credit?->credit_hour }}">
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="enforce_prereq" value="1"
                                                                id="enf{{ $offering->id }}"
                                                                {{ $offering->enforce_prereq ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="enf{{ $offering->id }}">
                                                                Enforce prerequisite completion
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light rounded-bottom-4 border-0">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No selections yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @php
                        $totalCredit = ($offerings ?? collect())->reduce(function ($carry, $o) {
                            $val = $o->credit_hour ?? optional(optional($o->subject)->credit)->credit_hour;
                            return $carry + (float) ($val ?? 0);
                        }, 0);
                    @endphp
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-semibold px-3">Total Credit</td>
                            <td id="totalCreditCell" class="fw-semibold ">{{ number_format($totalCredit, 1) }}</td>
                            <td></td>
                            <td class="text-end"><button id="sendToCourseOffering" type="button"
                                    class="btn btn-primary btn-sm">Submit</button></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <script>
                (function() {
                    const byId = (id) => document.getElementById(id);
                    const yearSel = byId('filterYear');
                    const typeSel = byId('filterType');
                    const classSel = byId('filterClass');
                    const body = byId('offeringsBody');
                    const totalCell = byId('totalCreditCell');

                    function applyFilters() {
                        const y = yearSel.value;
                        const t = typeSel.value;
                        const c = classSel.value;
                        let total = 0;
                        Array.from(body.querySelectorAll('tr')).forEach((row) => {
                            const show = (!y || row.dataset.year === y) &&
                                (!t || row.dataset.type === t) &&
                                (!c || row.dataset.class === c);
                            row.style.display = show ? '' : 'none';
                            if (show) total += Number(row.dataset.credit || 0);
                        });
                        if (totalCell) totalCell.textContent = total.toFixed(1);
                    }

                    ['change'].forEach(evt => {
                        yearSel.addEventListener(evt, applyFilters);
                        typeSel.addEventListener(evt, applyFilters);
                        classSel.addEventListener(evt, applyFilters);
                    });

                    applyFilters();


                    // On submit: send all (visible) subjects to Course Offering page
                    const submitBtn = byId('sendToCourseOffering');
                    if (submitBtn) {
                        submitBtn.addEventListener('click', function() {
                            // Gather subject IDs from currently visible rows
                            const subjectIds = [];
                            Array.from(body.querySelectorAll('tr')).forEach((row) => {
                                if (row.style.display === 'none') return;
                                // Extract subject id from the Subject cell using data attribute (add it below if missing)
                                const sid = row.getAttribute('data-subject-id');
                                if (sid) subjectIds.push(Number(sid));
                            });
                            // Fallback: if no filters applied or no ids collected, collect all
                            if (subjectIds.length === 0) {
                                Array.from(body.querySelectorAll('tr')).forEach((row) => {
                                    const sid = row.getAttribute('data-subject-id');
                                    if (sid) subjectIds.push(Number(sid));
                                });
                            }
                            // Store in sessionStorage and redirect
                            try {
                                sessionStorage.setItem('preselected_subject_ids', JSON.stringify(subjectIds));
                            } catch (e) {}
                            window.location.href = "{{ route('courseoffering.index') }}";
                        });
                    }
                })();
            </script>
        </div>
    </div>
@endsection
