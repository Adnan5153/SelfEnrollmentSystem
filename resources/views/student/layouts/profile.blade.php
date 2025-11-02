@extends('layouts.student')

@section('content')
    @php
        $s = $student ?? null;
        $deptSubjects = 0;
        if ($s) {
            if (optional($s->department)->subjects) {
                $deptSubjects = $s->department->subjects->count();
            } elseif (!empty($s->department_id)) {
                $deptSubjects = \App\Models\Subject::where('department_id', $s->department_id)->count();
            }
        }
        $enrolledSubjects = $s ? $s->subjects->count() : 0;
        $remainingSubjects = max($deptSubjects - $enrolledSubjects, 0);
        $programName = $profile['program'] ?? (optional($s?->department)->name ?? 'Computer Science & Engineering');
    @endphp

    <div class="card shadow-lg border-0 rounded-4 mt-4">
        <div class="card-header bg-gradient bg-primary text-white rounded-top-4 border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-id-card me-2"></i>Student Profile
                </h4>
                <span class="badge bg-light text-dark px-3 py-2">
                    {{ $programName }}
                </span>
            </div>
        </div>

        <div class="card-body bg-light rounded-bottom-4 p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                    style="width:72px;height:72px;background:#0d6efd1a;">
                    <span class="fw-bold text-primary fs-4">
                        {{ strtoupper(Str::of($s->name ?? 'John Doe')->explode(' ')->map(fn($p) => Str::substr($p, 0, 1))->take(2)->implode('')) }}
                    </span>
                </div>
                <div>
                    <h5 class="mb-1 fw-bold text-dark">{{ $s->name ?? 'John Doe' }}</h5>
                    <div class="text-muted small"><i
                            class="fa-solid fa-envelope me-1"></i>{{ $s->email ?? 'john.doe@example.com' }}</div>
                    <div class="text-muted small">
                        <i class="fa-solid fa-school me-1"></i>{{ optional($s?->class)->class_name ?? 'Semester 1' }}
                        <span class="mx-1">•</span>Section {{ $s->section ?? 'A' }}
                    </div>
                </div>
                <div class="ms-auto d-none d-md-block">
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill"><i
                            class="fa-solid fa-pen me-1"></i>Edit Profile</a>
                </div>
            </div>

            <div class="row row-cols-5 g-3 mb-4 flex-nowrap overflow-auto">
                <div class="col">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-body py-1">
                            <div class="text-muted small">Credits Earned</div>
                            <div class="fs-5 fw-semibold">{{ number_format((float) ($creditEarned ?? 0), 1) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-body py-1">
                            <div class="text-muted small">Credits Remaining</div>
                            <div class="fs-5 fw-semibold">{{ number_format((float) ($creditRemaining ?? 0), 1) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-body py-1">
                            <div class="text-muted small">Total Subjects</div>
                            <div class="fs-5 fw-semibold">{{ $deptSubjects }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-body py-1">
                            <div class="text-muted small">Completed Subjects</div>
                            <div class="fs-5 fw-semibold">{{ $completedSubjects ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-body py-1">
                            <div class="text-muted small">Courses Enrolled</div>
                            <div class="fs-5 fw-semibold">{{ $enrolledSubjects }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-header bg-white border-0 fw-semibold">
                            <i class="fa-solid fa-user me-2 text-primary"></i>Personal Information
                        </div>
                        <div class="card-body pt-2">
                            <div class="mb-2">
                                <div class="text-muted small">Full Name</div>
                                <div class="fw-semibold">{{ $s->name ?? 'John Doe' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Email</div>
                                <div class="fw-semibold">{{ $s->email ?? 'john.doe@example.com' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Gender</div>
                                <div class="fw-semibold">{{ $profile['gender'] ?? 'Male' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Date of Birth</div>
                                <div class="fw-semibold">{{ $profile['dob'] ?? '2003-05-14' }}</div>
                            </div>
                            <div>
                                <div class="text-muted small">Religion</div>
                                <div class="fw-semibold">{{ $profile['religion'] ?? 'Islam' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-header bg-white border-0 fw-semibold">
                            <i class="fa-solid fa-graduation-cap me-2 text-primary"></i>Academic Information
                        </div>
                        <div class="card-body pt-2">
                            <div class="mb-2">
                                <div class="text-muted small">Department</div>
                                <div class="fw-semibold">{{ $programName }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Program</div>
                                <div class="fw-semibold">{{ $profile['program'] ?? 'B.Sc. in CSE' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Semester / Section</div>
                                <div class="fw-semibold">{{ optional($s?->class)->class_name ?? 'Semester 1' }} •
                                    {{ $s->section ?? 'A' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Student ID</div>
                                <div class="fw-semibold">{{ $s?->id }}</div>
                            </div>
                            <div>
                                <div class="text-muted small">Advisor</div>
                                <div class="fw-semibold">{{ $profile['advisor'] ?? 'Dr. Jane Smith' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-header bg-white border-0 fw-semibold d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-location-dot me-2 text-primary"></i>Contact</span>
                            @if($parent)
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editContactModal">
                                <i class="fa-solid fa-pen me-1"></i>Edit
                            </button>
                            @endif
                        </div>
                        <div class="card-body pt-2">
                            <div class="mb-2">
                                <div class="text-muted small">Phone</div>
                                <div class="fw-semibold">{{ $profile['phone'] ?? '+880 1XXX-XXXXXX' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Present Address</div>
                                <div class="fw-semibold">
                                    {{ $profile['present_address'] ?? 'House 12, Road 5, Chattogram' }}</div>
                            </div>
                            <div>
                                <div class="text-muted small">Permanent Address</div>
                                <div class="fw-semibold">{{ $profile['permanent_address'] ?? 'Cumilla, Bangladesh' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-header bg-white border-0 fw-semibold d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-people-roof me-2 text-primary"></i>Parent/Guardian</span>
                            @if($parent)
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editParentModal">
                                <i class="fa-solid fa-pen me-1"></i>Edit
                            </button>
                            @endif
                        </div>
                        <div class="card-body pt-2">
                            <div class="mb-2">
                                <div class="text-muted small">Father's Name</div>
                                <div class="fw-semibold">{{ $profile['father_name'] ?? 'Mr. Abdullah' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Mother's Name</div>
                                <div class="fw-semibold">{{ $profile['mother_name'] ?? 'Mrs. Fatema' }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Occupation</div>
                                <div class="fw-semibold">{{ $profile['parent_occupation'] ?? 'Business / Teacher' }}</div>
                            </div>
                            <div>
                                <div class="text-muted small">Parent Email</div>
                                <div class="fw-semibold">{{ $profile['parent_email'] ?? 'parent@example.com' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill"><i
                        class="fa-solid fa-download me-1"></i>Download PDF</a>
            </div>
        </div>
    </div>

    @if($parent)
    <!-- Modal for editing Contact Information -->
    <div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="editContactLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 border-0">
                    <h5 class="modal-title fw-semibold" id="editContactLabel">
                        <i class="fa-solid fa-location-dot me-2"></i>Edit Contact Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('student.profile.update.contact') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body bg-light py-4 px-4">
                        <div class="mb-3">
                            <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" class="form-control rounded-3 shadow-sm" id="phone_number" name="phone_number" value="{{ $parent->phone_number ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="present_address" class="form-label fw-semibold">Present Address</label>
                            <input type="text" class="form-control rounded-3 shadow-sm" id="present_address" name="present_address" value="{{ $parent->present_address ?? '' }}" required>
                        </div>
                        <div class="mb-0">
                            <label for="permanent_address" class="form-label fw-semibold">Permanent Address</label>
                            <input type="text" class="form-control rounded-3 shadow-sm" id="permanent_address" name="permanent_address" value="{{ $parent->permanent_address ?? '' }}" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-3 px-4 border-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">
                            <i class="fa-solid fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for editing Parent Information -->
    <div class="modal fade" id="editParentModal" tabindex="-1" aria-labelledby="editParentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 border-0">
                    <h5 class="modal-title fw-semibold" id="editParentLabel">
                        <i class="fa-solid fa-people-roof me-2"></i>Edit Parent Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('student.profile.update.parent') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body bg-light py-4 px-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="father_name" class="form-label fw-semibold">Father's Name</label>
                                <input type="text" class="form-control rounded-3 shadow-sm" id="father_name" name="father_name" value="{{ $parent->father_name ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="mother_name" class="form-label fw-semibold">Mother's Name</label>
                                <input type="text" class="form-control rounded-3 shadow-sm" id="mother_name" name="mother_name" value="{{ $parent->mother_name ?? '' }}" required>
                            </div>
                        </div>
                        <div class="row g-3 mt-0">
                            <div class="col-md-6">
                                <label for="father_occupation" class="form-label fw-semibold">Father's Occupation</label>
                                <input type="text" class="form-control rounded-3 shadow-sm" id="father_occupation" name="father_occupation" value="{{ $parent->father_occupation ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="mother_occupation" class="form-label fw-semibold">Mother's Occupation</label>
                                <input type="text" class="form-control rounded-3 shadow-sm" id="mother_occupation" name="mother_occupation" value="{{ $parent->mother_occupation ?? '' }}">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="parent_email" class="form-label fw-semibold">Parent Email</label>
                            <input type="email" class="form-control rounded-3 shadow-sm" id="parent_email" name="parent_email" value="{{ $parent->parent_email ?? '' }}" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-3 px-4 border-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">
                            <i class="fa-solid fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
        <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
        <i class="fa-solid fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
        <i class="fa-solid fa-exclamation-circle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
@endsection
