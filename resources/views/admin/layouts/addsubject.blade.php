@extends('layouts.admin')

@section('content')
    <div class="container my-3 mt-5 p-0">
        <!-- Add Subject Form -->
        <div class="bg-light p-3 rounded-3 shadow-sm mb-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif



            <div class="text-center mb-3">
                <h3 class="fw-bold text-dark">Add Subject</h3>
                <p class="text-secondary">Fill in the details below to add a new subject</p>
            </div>

            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <h5 class="fw-semibold text-dark mb-3">Subject Information</h5>
                    <div class="row g-3">
                        <!-- Row 1 -->
                        <div class="col-md-4">
                            <label for="name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter subject name" value="{{ old('name') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code"
                                placeholder="Enter subject code" value="{{ old('subject_code') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="year" class="form-label">Year</label>
                            <select class="form-select" id="year" name="year">
                                <option value="" selected disabled>Select year</option>
                                <option value="1st Year" {{ old('year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                <option value="Technical Electives"
                                    {{ old('year') == 'Technical Electives' ? 'selected' : '' }}>Technical Electives
                                </option>
                            </select>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-4">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select" id="department_id" name="department_id">
                                <option value="" selected disabled>Select department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="credit_id" class="form-label">Subject Type</label>
                            <select class="form-select" id="credit_id" name="credit_id">
                                <option value="" selected disabled>Select subject type</option>
                                @foreach ($credits as $credit)
                                    <option value="{{ $credit->id }}"
                                        {{ old('credit_id') == $credit->id ? 'selected' : '' }}>
                                        {{ ucfirst($credit->subject_type) }} ({{ $credit->credit_hour }} credit)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="teacher_id" class="form-label">Assign Teacher</label>
                            <select class="form-select" id="teacher_id" name="teacher_id">
                                <option value="" selected disabled>Select a teacher</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} - {{ $teacher->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">Submit</button>
                    <button type="reset" class="btn btn-danger px-4 py-2 rounded-pill">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- List of Subjects with Filtering -->
    <div class="card shadow-lg border-0 rounded-4 mb-5">
        <div class="card-header bg-gradient bg-dark text-white rounded-top-4 border-0 py-3 px-4">
            <div class="row align-items-center w-100 g-2">
                <div class="col-lg-3 col-md-6 col-12 mb-2 mb-lg-0">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list"></i> List of Subjects
                    </h5>
                </div>
                <div class="col-lg-9 col-md-12 col-12">
                    <form class="row gx-2 gy-1 align-items-center justify-content-end">
                        <!-- Search Bar -->
                        <div class="col-auto flex-grow-1" style="min-width: 220px;">
                            <div class="position-relative">
                                <input type="text" class="form-control-sm ps-5" id="search_subject"
                                    placeholder="Search by subject name...">
                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                                    <i class="fa-solid fa-search text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Year Filter -->
                        <div class="col-auto" style="min-width: 160px;">
                            <select class="form-select form-select-sm" id="filter_year">
                                <option value="">Filter by Year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="Technical Electives">Technical Electives</option>
                            </select>
                        </div>
                        <!-- Subject Type Filter -->
                        <div class="col-auto" style="min-width: 180px;">
                            <select class="form-select form-select-sm" id="filter_credit">
                                <option value="">Filter by Subject Type</option>
                                @foreach ($credits as $credit)
                                    <option value="{{ $credit->id }}">
                                        {{ ucfirst($credit->subject_type) }} ({{ $credit->credit_hour }} credit)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Department Filter -->
                        <div class="col-auto" style="min-width: 180px;">
                            <select class="form-select form-select-sm" id="filter_department">
                                <option value="">Filter by Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body bg-light rounded-bottom-4 p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle rounded-4 overflow-hidden mb-0">
                    <thead class="table-success">
                        <tr>
                            <th class="text-center" style="width: 4%">#</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Year</th>
                            <th>Department</th>
                            <th>Subject Type</th>
                            <th>Credit Hour</th>
                            <th>Assigned Teacher</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="subjectTable">
                        @foreach ($subjects as $subject)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $subject->name }}</td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                                        {{ $subject->subject_code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3">
                                        {{ $subject->year ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3">
                                        {{ $subject->department->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3">
                                        {{ ucfirst($subject->credit->subject_type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-3">
                                        {{ $subject->credit->credit_hour ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">
                                        {{ $subject->teacher->name ?? 'No Teacher Assigned' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!-- Trigger Edit Modal -->
                                    <a href="#" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm me-1"
                                        data-bs-toggle="modal" data-bs-target="#editSubjectModal-{{ $subject->id }}"
                                        title="Edit Subject">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <!-- Delete -->
                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                            onclick="return confirm('Are you sure?')" title="Delete Subject">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSubjectModal-{{ $subject->id }}" tabindex="-1"
                                aria-labelledby="editSubjectModalLabel-{{ $subject->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 shadow">
                                        <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-dark text-white border-0 rounded-top-4">
                                                <h5 class="modal-title" id="editSubjectModalLabel-{{ $subject->id }}">
                                                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Subject
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body bg-light p-4">
                                                <div class="mb-3">
                                                    <label for="name-{{ $subject->id }}" class="form-label">Subject
                                                        Name</label>
                                                    <input type="text" class="form-control"
                                                        id="name-{{ $subject->id }}" name="name"
                                                        value="{{ $subject->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subject_code-{{ $subject->id }}"
                                                        class="form-label">Subject
                                                        Code</label>
                                                    <input type="text" class="form-control"
                                                        id="subject_code-{{ $subject->id }}" name="subject_code"
                                                        value="{{ $subject->subject_code }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="year-{{ $subject->id }}" class="form-label">Year</label>
                                                    <select class="form-select" id="year-{{ $subject->id }}"
                                                        name="year" required>
                                                        <option value="" selected disabled>Select year</option>
                                                        <option value="1st Year"
                                                            {{ $subject->year == '1st Year' ? 'selected' : '' }}>1st Year
                                                        </option>
                                                        <option value="2nd Year"
                                                            {{ $subject->year == '2nd Year' ? 'selected' : '' }}>2nd Year
                                                        </option>
                                                        <option value="3rd Year"
                                                            {{ $subject->year == '3rd Year' ? 'selected' : '' }}>3rd Year
                                                        </option>
                                                        <option value="4th Year"
                                                            {{ $subject->year == '4th Year' ? 'selected' : '' }}>4th Year
                                                        </option>
                                                        <option value="Technical Electives"
                                                            {{ $subject->year == 'Technical Electives' ? 'selected' : '' }}>
                                                            Technical Electives</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="department_id-{{ $subject->id }}"
                                                        class="form-label">Department</label>
                                                    <select class="form-select" id="department_id-{{ $subject->id }}"
                                                        name="department_id" required>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ $department->id == $subject->department_id ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="credit_id-{{ $subject->id }}" class="form-label">Subject
                                                        Type</label>
                                                    <select class="form-select" id="credit_id-{{ $subject->id }}"
                                                        name="credit_id" required>
                                                        @foreach ($credits as $credit)
                                                            <option value="{{ $credit->id }}"
                                                                {{ $credit->id == $subject->credit_id ? 'selected' : '' }}>
                                                                {{ ucfirst($credit->subject_type) }}
                                                                ({{ $credit->credit_hour }} credit)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="teacher_id-{{ $subject->id }}" class="form-label">Assign
                                                        Teacher</label>
                                                    <select class="form-select" id="teacher_id-{{ $subject->id }}"
                                                        name="teacher_id" required>
                                                        @foreach ($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}"
                                                                {{ $teacher->id == $subject->teacher_id ? 'selected' : '' }}>
                                                                {{ $teacher->name }} - {{ $teacher->email }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div
                                                class="modal-footer bg-light rounded-bottom-4 border-0 d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary rounded-pill px-3"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit"
                                                    class="btn btn-primary rounded-pill px-3">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Filter Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let searchInput = document.getElementById("search_subject");
            let yearDropdown = document.getElementById("filter_year");
            let filterDropdown = document.getElementById("filter_credit");
            let departmentDropdown = document.getElementById("filter_department");
            let subjectTableBody = document.getElementById("subjectTable");

            function fetchFilteredSubjects() {
                let searchTerm = searchInput.value.trim();
                let yearValue = yearDropdown.value;
                let creditId = filterDropdown.value;
                let departmentId = departmentDropdown.value;

                let params = new URLSearchParams();

                if (searchTerm) params.append('search', searchTerm);
                if (yearValue) params.append('year', yearValue);
                if (creditId) params.append('credit_id', creditId);
                if (departmentId) params.append('department_id', departmentId);

                let url = `/admin/subjects/filter?${params.toString()}`;

                fetch(url)
                    .then(r => r.ok ? r.json() : Promise.reject(r.status))
                    .then(data => {
                        if (!data.length) {
                            subjectTableBody.innerHTML = `
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="fa-solid fa-search me-2"></i>No subjects found matching your criteria
                                    </td>
                                </tr>`;
                            return;
                        }

                        subjectTableBody.innerHTML = data.map((s, idx) => `
                            <tr>
                                <td class="text-center fw-bold">${idx + 1}</td>
                                <td class="fw-semibold">${s.name}</td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                                        ${s.subject_code}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3">
                                        ${s.year}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3">
                                        ${s.department_name}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3">
                                        ${s.subject_type}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-3">
                                        ${s.credit_hour}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark rounded-pill px-3">
                                        ${s.teacher_name}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm me-1"
                                       data-bs-toggle="modal" data-bs-target="#editSubjectModal-${s.id}"
                                       title="Edit Subject">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="/admin/subjects/${s.id}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit"
                                            class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                            onclick="return confirm('Are you sure?')" title="Delete Subject">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `).join('');
                    })
                    .catch(err => {
                        console.error("Error fetching subjects:", err);
                        subjectTableBody.innerHTML = `
                            <tr>
                                <td colspan="9" class="text-center text-danger">
                                    <i class="fa-solid fa-exclamation-triangle me-2"></i>Error loading subjects
                                </td>
                            </tr>`;
                    });
            }

            // Add event listeners for all filters
            searchInput.addEventListener("input", debounce(fetchFilteredSubjects, 300));
            yearDropdown.addEventListener("change", fetchFilteredSubjects);
            filterDropdown.addEventListener("change", fetchFilteredSubjects);
            departmentDropdown.addEventListener("change", fetchFilteredSubjects);

            // Debounce function to prevent too many API calls while typing
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
@endsection
