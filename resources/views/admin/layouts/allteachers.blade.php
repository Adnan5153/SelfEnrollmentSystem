@extends('layouts.admin')
@section('content')
    <div class="card border-0 shadow-lg rounded-4 mt-5">
        <!-- Header: Title + Search Boxes + Button -->
        <div
            class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex flex-wrap align-items-center justify-content-between gap-2 px-4 py-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-users"></i> Teacher List
                </h5>
            </div>
            <form action="{{ route('allteachers.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap"
                style="min-width: 330px;">
                <input type="text" name="search_name" class="form-control form-control-sm rounded-pill mb-0"
                    placeholder="Search Name" value="{{ request('search_name') }}"
                    style="width: 130px; min-width: 100px; max-width: 150px; height: 36px;">
                <input type="text" name="search_subject" class="form-control form-control-sm rounded-pill mb-0"
                    placeholder="Search Subject" value="{{ request('search_subject') }}"
                    style="width: 130px; min-width: 100px; max-width: 150px; height: 36px;">
                <button type="submit"
                    class="btn btn-primary btn-sm rounded-pill d-flex align-items-center justify-content-center"
                    style="height: 36px; width: 36px; min-width: 36px;">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 rounded-3 overflow-hidden">
                    <thead class="table-info">
                        <tr>
                            <th class="text-black text-center" style="width: 5%;">ID</th>
                            <th class="text-black">Name</th>
                            <th class="text-black">Gender</th>
                            <th class="text-black">Department</th>
                            <th class="text-black">Class</th>
                            <th class="text-black">Section</th>
                            <th class="text-black">Address</th>
                            <th class="text-black">Date of Birth</th>
                            <th class="text-black">Phone No</th>
                            <th class="text-black">E-mail</th>
                            <th class="text-center text-black" style="width: 7%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teachers as $teacher)
                            <tr>
                                <td class="text-center text-black fw-bold">{{ $teacher->id ?? 'N/A' }}</td>
                                <td class="text-black">{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                <td class="text-black">{{ $teacher->gender }}</td>
                                <td class="text-black">{{ $teacher->department->name ?? 'N/A' }}</td>
                                <td class="text-black">{{ $teacher->class->class_name ?? 'N/A' }}</td>
                                <td class="text-black">{{ $teacher->section }}</td>
                                <td class="text-black">{{ $teacher->address }}</td>
                                <td class="text-black">{{ $teacher->date_of_birth }}</td>
                                <td class="text-black">{{ $teacher->phone }}</td>
                                <td class="text-black">{{ $teacher->email }}</td>
                                <td class="text-center">
                                    <!-- Edit Button -->
                                    <a href="#" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editTeacherModal-{{ $teacher->id ?? 'missing' }}"
                                        title="Edit Teacher">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <!-- Delete Button -->
                                    @if (!empty($teacher->id))
                                        <form action="{{ route('allteachers.destroy', $teacher->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm rounded-circle shadow-sm ms-1"
                                                onclick="return confirm('Are you sure you want to delete this teacher?')"
                                                title="Delete Teacher">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-danger small">Missing ID</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>


    <!-- Include the modal partial for each teacher -->
    @foreach ($teachers as $teacher)
        @if (!empty($teacher->id))
            @include('admin.layouts.modal.teachermodal', ['teacher' => $teacher])
        @endif
    @endforeach
@endsection
