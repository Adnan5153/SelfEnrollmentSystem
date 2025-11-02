@extends('layouts.admin')

@section('content')

    <!-- Offer Subjects to Class Form -->
    <div class="bg-light p-3 rounded-3 shadow-sm mb-4 mt-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="text-center mb-3">
            <h3 class="fw-bold text-dark">
                {{ isset($class) ? 'Update Subject Offerings' : 'Offer Subjects to Class' }}
            </h3>
            <p class="text-secondary">
                {{ isset($class) ? 'Modify subjects offered to the selected class' : 'Select a class and assign the subjects it will offer to students' }}
            </p>
        </div>

        <form action="{{ isset($class) ? route('courseoffering.update', $class->id) : route('courseoffering.store') }}"
            method="POST">
            @csrf
            @if (isset($class))
                @method('PUT')
            @endif
            <div class="mb-2">
                <h5 class="fw-semibold text-dark mb-2">Course Offering Information</h5>
                <div class="row g-3">
                    <!-- Class Dropdown -->
                    <div class="col-md-4">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select text-dark bg-white" id="class_id" name="class_id" required
                            {{ isset($class) ? 'disabled' : '' }}>
                            <option value="" selected disabled>Select a class</option>
                            @foreach ($classes as $c)
                                <option value="{{ $c->id }}"
                                    {{ isset($class) && $c->id === $class->id ? 'selected' : '' }}>
                                    {{ $c->class_name }}
                                </option>
                            @endforeach
                        </select>
                        @if (isset($class))
                            <input type="hidden" name="class_id" value="{{ $class->id }}">
                        @endif
                    </div>

                    <!-- Dual List Box -->
                    <div class="col-md-8">
                        <label class="form-label">Subjects to Offer</label>
                        <div class="row">
                            <!-- Available Subjects with checkboxes -->
                            <div class="col-md-5">
                                <input type="text" class="form-control mb-2" id="searchSubjects"
                                    placeholder="Search subjects...">
                                <div class="list-group" id="availableSubjects" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($subjects as $subject)
                                        @php
                                            $isSelected =
                                                isset($offered_subjects) &&
                                                in_array($subject->id, $offered_subjects ?? []);
                                        @endphp
                                        @if (!$isSelected)
                                            <label class="list-group-item d-flex align-items-center"
                                                data-name="{{ strtolower($subject->name) }}">
                                                <input class="form-check-input me-2 available-subject-checkbox"
                                                    type="checkbox" value="{{ $subject->id }}">
                                                <span>{{ $subject->name }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                                <button type="button" class="btn btn-outline-primary mb-2 w-100"
                                    onclick="moveCheckedSubjects()">
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger w-100"
                                    onclick="moveSelectedSubjectsBack()">
                                    <i class="fa fa-arrow-left"></i>
                                </button>
                            </div>

                            <!-- Selected Subjects -->
                            <div class="col-md-5">
                                <label class="form-label">Subjects Selected <small class="text-muted">(Ctrl+Click to select
                                        multiple for removal)</small></label>
                                <select multiple class="form-select" id="selectedSubjects" name="subject_ids[]"
                                    size="10">
                                    @if (isset($offered_subjects))
                                        @foreach ($subjects as $subject)
                                            @if (in_array($subject->id, $offered_subjects))
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                    {{ isset($class) ? 'Update' : 'Submit' }}
                </button>
                <button type="reset" class="btn btn-danger px-4 py-2 rounded-pill"
                    onclick="location.reload()">Reset</button>
                @if (isset($class))
                    <a href="{{ route('courseoffering.index') }}"
                        class="btn btn-secondary px-4 py-2 rounded-pill">Cancel</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Offered Subjects Table -->
    <div class="card shadow-lg border-0 rounded-4 mt-4">
        <div class="card-header bg-dark text-white rounded-top-4 border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-table-list me-2"></i>Offered Subjects Table</h5>
            <div class="d-flex align-items-center gap-2">
                <label for="classFilter" class="mb-0 small text-white-50">Filter by Class:</label>
                <select id="classFilter" class="form-select form-select-sm w-auto">
                    <option value="all">All Classes</option>
                    @foreach ($classes as $c)
                        <option value="class-{{ $c->id }}">{{ $c->class_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body bg-light rounded-bottom-4 p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="offeredSubjectsTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Class</th>
                            <th>Subject Name</th>
                            <th class="text-center">Subject Code</th>
                            <th class="text-center">Year</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Credits</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Prereq Enforced</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($offerings ?? [] as $index => $offering)
                            @php
                                $rowClass = 'class-' . ($offering->class_id ?? 'none');
                            @endphp
                            <tr class="offering-row {{ $rowClass }}" data-class-id="{{ $offering->class_id ?? '' }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-info text-white">{{ $offering->class?->class_name ?? 'N/A' }}</span>
                                </td>
                                <td class="fw-semibold">{{ $offering->subject?->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $offering->subject?->subject_code ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">{{ $offering->subject?->year ?? 'N/A' }}</td>
                                <td class="text-center">{{ $offering->subject?->department?->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary text-white">
                                        {{ $offering->credit_hour ?? $offering->subject?->credit?->credit_hour ?? '0' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $type = strtolower($offering->subject?->credit?->subject_type ?? 'theory');
                                    @endphp
                                    <span class="badge {{ $type === 'lab' ? 'bg-success-subtle text-success-emphasis' : 'bg-info-subtle text-info-emphasis' }}">
                                        {{ ucfirst($type) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $offering->enforce_prereq ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">
                                        {{ $offering->enforce_prereq ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form method="POST" action="{{ route('courseoffering.selected.delete', $offering->id) }}"
                                            class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to remove this offering?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Offering">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                    No offered subjects found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (isset($offerings) && $offerings->count() > 0)
                        @php
                            $totalCredits = $offerings->sum(function($o) {
                                return (float) ($o->credit_hour ?? $o->subject?->credit?->credit_hour ?? 0);
                            });
                        @endphp
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="6" class="text-end fw-semibold">Total Credits Offered:</td>
                                <td class="text-center fw-bold text-primary">{{ number_format($totalCredits, 1) }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Move checked subjects from the available list to selected
        function moveCheckedSubjects() {
            const availableList = document.getElementById('availableSubjects');
            const selectedBox = document.getElementById('selectedSubjects');
            let moved = false;
            availableList.querySelectorAll('.available-subject-checkbox:checked').forEach(checkbox => {
                let option = document.createElement('option');
                option.value = checkbox.value;
                option.textContent = checkbox.parentElement.textContent.trim();
                option.selected = false; // Don't visually select, will be auto-selected on submit
                selectedBox.appendChild(option);
                checkbox.parentElement.remove();
                moved = true;
            });
            if (moved) {
                sortSelectOptions(selectedBox);
                // Clear all checkboxes after moving
                availableList.querySelectorAll('.available-subject-checkbox').forEach(cb => cb.checked = false);
            }
        }

        // Move selected options in the right box back to available
        function moveSelectedSubjectsBack() {
            const availableList = document.getElementById('availableSubjects');
            const selectedBox = document.getElementById('selectedSubjects');
            const selectedOptions = Array.from(selectedBox.selectedOptions);

            // If nothing is selected, don't do anything (or you could move the last one, or show a message)
            if (selectedOptions.length === 0) {
                return; // Do nothing if nothing is selected
            }

            selectedOptions.forEach(option => {
                // Create the label with checkbox again
                let label = document.createElement('label');
                label.className = 'list-group-item d-flex align-items-center';
                label.setAttribute('data-name', option.textContent.trim().toLowerCase());

                let checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input me-2 available-subject-checkbox';
                checkbox.value = option.value;

                label.appendChild(checkbox);
                label.appendChild(document.createTextNode(' ' + option.textContent.trim()));
                availableList.appendChild(label);

                // Remove option from the selected list
                option.remove();
            });
            sortAvailableSubjects();
        }

        // Ensure all options are selected before form submission
        document.querySelectorAll('form').forEach(form => {
            if (form.action.includes('courseoffering')) {
                form.addEventListener('submit', function() {
                    const selectedBox = document.getElementById('selectedSubjects');
                    if (selectedBox) {
                        // Select all options in the multiple select so they get submitted
                        Array.from(selectedBox.options).forEach(option => {
                            option.selected = true;
                        });
                    }
                });
            }
        });

        // Sort selected subjects alphabetically
        function sortSelectOptions(selectElement) {
            let options = Array.from(selectElement.options);
            options.sort((a, b) => a.textContent.localeCompare(b.textContent));
            selectElement.innerHTML = '';
            options.forEach(option => selectElement.appendChild(option));
        }

        // Sort available subjects alphabetically
        function sortAvailableSubjects() {
            let items = Array.from(document.querySelectorAll('#availableSubjects .list-group-item'));
            items.sort((a, b) => a.textContent.localeCompare(b.textContent));
            const availableList = document.getElementById('availableSubjects');
            availableList.innerHTML = '';
            items.forEach(item => availableList.appendChild(item));
        }

        // Search/filter for available subjects
        document.getElementById('searchSubjects').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#availableSubjects .list-group-item').forEach(label => {
                const name = label.getAttribute('data-name');
                label.style.display = name.includes(filter) ? '' : 'none';
            });
        });

        // Class filter for offered subjects table
        window.addEventListener('DOMContentLoaded', () => {
            sortAvailableSubjects();
            const classFilter = document.getElementById('classFilter');
            if (classFilter) {
                classFilter.addEventListener('change', function() {
                    const selected = this.value;
                    const rows = document.querySelectorAll('#offeredSubjectsTable tbody .offering-row');
                    let visibleCount = 0;
                    rows.forEach(row => {
                        const shouldShow = selected === 'all' || row.classList.contains(selected);
                        row.style.display = shouldShow ? '' : 'none';
                        if (shouldShow) visibleCount++;
                    });
                    
                    // Update empty message visibility
                    const emptyRow = document.querySelector('#offeredSubjectsTable tbody tr td[colspan="10"]');
                    if (emptyRow && emptyRow.closest('tr')) {
                        emptyRow.closest('tr').style.display = visibleCount === 0 ? '' : 'none';
                    }
                });
            }

            // Prefill Selected Subjects from sessionStorage (coming from Selected Offerings page)
            try {
                const raw = sessionStorage.getItem('preselected_subject_ids');
                if (raw) {
                    const ids = JSON.parse(raw);
                    const availableList = document.getElementById('availableSubjects');
                    const selectedBox = document.getElementById('selectedSubjects');
                    const alreadySelected = new Set(Array.from(selectedBox.options).map(o => Number(o.value)));

                    (ids || []).forEach((id) => {
                        if (alreadySelected.has(Number(id))) return;
                        const checkbox = availableList.querySelector(
                            `.available-subject-checkbox[value="${id}"]`);
                        if (checkbox) {
                            const option = document.createElement('option');
                            option.value = String(id);
                            option.textContent = checkbox.parentElement.textContent.trim();
                            option.selected = true;
                            selectedBox.appendChild(option);
                            // Remove from available
                            checkbox.parentElement.remove();
                        }
                    });

                    sortSelectOptions(selectedBox);
                    sortAvailableSubjects();
                    sessionStorage.removeItem('preselected_subject_ids');
                }
            } catch (e) {}
        });
    </script>
@endsection
