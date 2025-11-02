@extends('layouts.teacher')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-dark text-white rounded-top-4 border-0 py-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clipboard-check fa-lg"></i>
                    <span class="fw-semibold fs-5">Add Marks for Students</span>
                </div>
                <div class="card-body bg-light rounded-bottom-4 p-4">
                    @if (session('success'))
                        <div class="alert alert-success shadow-sm d-flex align-items-center gap-2">
                            <i class="fa-solid fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('teacher.storemarks') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        {{-- Subject Dropdown --}}
                        <div class="form-floating mb-3">
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                <option value="" selected disabled>Select Subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <label for="subject_id"><i class="fa-solid fa-book-open me-1"></i> Subject</label>
                            <div class="invalid-feedback">
                                Please select a subject.
                            </div>
                        </div>

                        {{-- Student Dropdown --}}
                        <div class="form-floating mb-3">
                            <select class="form-select" id="student_id" name="student_id" required>
                                <option value="" selected disabled>Select Student</option>
                            </select>
                            <label for="student_id"><i class="fa-solid fa-user-graduate me-1"></i> Student</label>
                            <div class="invalid-feedback">
                                Please select a student.
                            </div>
                        </div>

                        {{-- Marks --}}
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="marks" name="marks"
                                placeholder="Enter marks" min="0" max="100" required>
                            <label for="marks"><i class="fa-solid fa-pen-ruler me-1"></i> Marks (0-100)</label>
                            <div class="invalid-feedback">
                                Please enter marks between 0 and 100.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-circle-plus me-2"></i> Add Marks
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- AJAX Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectDropdown = document.getElementById('subject_id');
            subjectDropdown.addEventListener('change', function() {
                const subjectId = this.value;
                const studentSelect = document.getElementById('student_id');
                studentSelect.innerHTML = '<option value="" selected disabled>Loading...</option>';
                if (subjectId) {
                    fetch("{{ route('teacher.fetchstudents.bysubject') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                subject_id: subjectId
                            })
                        })
                        .then(res => res.json())
                        .then(students => {
                            studentSelect.innerHTML =
                                '<option value="" selected disabled>Select Student</option>';
                            if (students.length === 0) {
                                studentSelect.innerHTML +=
                                    '<option value="" disabled>No students enrolled</option>';
                            } else {
                                students.forEach(student => {
                                    const option = document.createElement('option');
                                    option.value = student.id;
                                    option.textContent = `${student.name} (ID: ${student.id})`;
                                    studentSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(() => {
                            studentSelect.innerHTML =
                                '<option value="" disabled>Error loading students</option>';
                        });
                }
            });

            // Bootstrap validation
            (() => {
                'use strict'
                const forms = document.querySelectorAll('.needs-validation')
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            })()
        });
    </script>
@endsection
