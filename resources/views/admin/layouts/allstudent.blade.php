@extends('layouts.admin')
@section('content')
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div
                    class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-users"></i> Student List
                        </h5>
                    </div>
                    <a href="{{ route('register.student.and.parent') }}"
                        class="btn btn-success btn-sm rounded-pill shadow-sm">
                        <i class="fa-solid fa-plus"></i> Add Student
                    </a>
                </div>
                <div class="card-body bg-light rounded-bottom-4">
                    {{-- Alerts --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive rounded-4">
                        <table class="table  align-middle mb-0 table-hover small">
                            <thead class="table-info">
                                <tr>
                                    <th class="text-center text-black" style="width:4%;">#</th>
                                    <th class="text-black">Student ID</th>
                                    <th class="text-black">Name</th>
                                    <th class="text-black">Gender</th>
                                    <th class="text-black">Father's Name</th>
                                    <th class="text-black">Mother's Name</th>
                                    <th class="text-black">Class</th>
                                    <th class="text-black">Section</th>
                                    <th class="text-black">Address</th>
                                    <th class="text-black">Date of Birth</th>
                                    <th class="text-black">Phone No</th>
                                    <th class="text-black">Email</th>
                                    <th class="text-center text-black" style="width:8%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr>
                                        <td class="text-center text-black fw-bold">{{ $loop->iteration }}</td>
                                        <td class="text-black">{{ $student->student_id }}</td>
                                        <td class="text-black">{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td class="text-black">{{ $student->gender }}</td>
                                        <td class="text-black">{{ optional($student->parent)->father_name ?? 'N/A' }}</td>
                                        <td class="text-black">{{ optional($student->parent)->mother_name ?? 'N/A' }}</td>
                                        <td class="text-black">{{ $student->class }}</td>
                                        <td class="text-black">{{ $student->section }}</td>
                                        <td class="text-black">{{ optional($student->parent)->present_address ?? 'N/A' }}
                                        </td>
                                        <td class="text-black">{{ $student->date_of_birth }}</td>
                                        <td class="text-black">{{ optional($student->parent)->phone_number ?? 'N/A' }}</td>
                                        <td class="text-black">{{ $student->email }}</td>
                                        <td class="text-center">
                                            <!-- Edit Modal Trigger -->
                                            <button type="button"
                                                class="btn btn-outline-warning btn-sm rounded-circle shadow-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStudentModal-{{ $student->student_id }}"
                                                title="Edit Student">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <!-- Delete -->
                                            <form action="{{ route('allstudent.destroy', $student->student_id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-circle shadow-sm ms-1"
                                                    onclick="return confirm('Are you sure you want to delete this student?')"
                                                    title="Delete Student">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-black-50">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $students->links() }}
                    </div>

                    {{-- Edit modals --}}
                    @foreach ($students as $student)
                        @include('admin.layouts.modal.edit', [
                            'student' => $student,
                            'parent' => $student->parent,
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
