@extends('layouts.admin')

@section('content')
    <div class="container my-3 mt-5 p-0">
        <!-- Add / Edit Prerequisite Form -->
        <div class="bg-light p-3 rounded-3 shadow-sm mb-3">
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
                <h3 class="fw-bold text-dark">Manage Prerequisites</h3>
                <p class="text-secondary">Select a subject and assign one or more prerequisite subjects. Credits are
                    optional.</p>
            </div>

            <form action="{{ route('prerequisite.store') }}" method="POST" id="prerequisiteForm">
                @csrf
                <div class="mb-2">
                    <h5 class="fw-semibold text-dark mb-2">Prerequisite Information</h5>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="subject_id" class="form-label">Target Subject</label>
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                <option value="" selected disabled>Select a subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="required_credits" class="form-label">Required Credits to Complete <span
                                    class="text-muted">(Optional)</span></label>
                            <input type="number" class="form-control" id="required_credits" name="required_credits"
                                placeholder="0" step="1" min="0" title="Please enter a valid whole number">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Prerequisite Subjects</label>
                            <div class="border rounded p-2" style="max-height: 250px; overflow-y: auto;">
                                <div class="row" id="prerequisite-list">
                                    @foreach ($subjects as $subject)
                                        <div class="col-md-12 prerequisite-option mb-2"
                                            data-subject-id="{{ $subject->id }}">
                                            <div class="card">
                                                <div class="card-body p-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="prerequisite_ids[]" id="prereq_{{ $subject->id }}"
                                                            value="{{ $subject->id }}">
                                                        <label class="form-check-label" for="prereq_{{ $subject->id }}">
                                                            {{ $subject->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">Save</button>
                    <button type="reset" class="btn btn-danger px-4 py-2 rounded-pill"
                        onclick="resetForm()">Reset</button>
                </div>
            </form>
        </div>

        <!-- List of Prerequisites -->
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fa-solid fa-list"></i> Prerequisite Mapping</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Prerequisites</th>
                                <th>Total Required Credits</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $index => $subject)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $subject->name }}</td>
                                    <td>
                                        @if ($subject->prerequisites->count())
                                            @foreach ($subject->prerequisites as $pre)
                                                <span class="badge bg-secondary">{{ $pre->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($subject->prerequisites->count())
                                            @php
                                                $totalCredits = $subject->prerequisites->sum('pivot.required_credits');
                                            @endphp
                                            <span class="badge bg-{{ $totalCredits > 0 ? 'info' : 'secondary' }}">
                                                {{ $totalCredits > 0 ? $totalCredits : 'No credits specified' }}
                                            </span>
                                        @else
                                            <span class="text-bold badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td class="d-flex gap-2">
                                        <form action="{{ route('prerequisite.destroy', $subject->id) }}" method="POST"
                                            onsubmit="return confirm('Remove all prerequisites for this subject?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill">Remove
                                                All</button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-info rounded-pill edit-btn"
                                            data-subject-id="{{ $subject->id }}"
                                            data-prerequisites='@json($subject->prerequisites->pluck('id'))'
                                            data-credits='@json($subject->prerequisites->pluck('pivot.required_credits', 'id'))'>
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No subjects found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelect = document.getElementById('subject_id');
            const checkboxes = document.querySelectorAll('.prerequisite-option');
            const prerequisiteForm = document.getElementById('prerequisiteForm');
            const requiredCreditsInput = document.getElementById('required_credits');

            function updateChecklistVisibility() {
                const selectedId = subjectSelect.value;
                checkboxes.forEach(option => {
                    option.style.display = option.dataset.subjectId === selectedId ? 'none' : 'block';
                });
            }

            function resetForm() {
                prerequisiteForm.reset();
                document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                requiredCreditsInput.value = '';
                updateChecklistVisibility();
            }

            if (requiredCreditsInput) {
                requiredCreditsInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, ''); // Allow only digits
                });
            }

            subjectSelect.addEventListener('change', updateChecklistVisibility);
            updateChecklistVisibility();

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const subjectId = button.dataset.subjectId;
                    const prerequisites = JSON.parse(button.dataset.prerequisites);
                    const credits = JSON.parse(button.dataset.credits || '{}');

                    subjectSelect.value = subjectId;
                    updateChecklistVisibility();

                    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked =
                        false);

                    prerequisites.forEach(id => {
                        const checkbox = document.getElementById(`prereq_${id}`);
                        if (checkbox) checkbox.checked = true;
                    });

                    if (prerequisites.length > 0 && credits[prerequisites[0]]) {
                        requiredCreditsInput.value = credits[prerequisites[0]];
                    } else {
                        requiredCreditsInput.value = '';
                    }
                });
            });
        });
    </script>
@endsection
