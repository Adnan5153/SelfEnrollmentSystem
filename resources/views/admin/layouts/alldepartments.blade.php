@extends('layouts.admin')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div
                    class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex flex-wrap align-items-center gap-2 px-4 py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list"></i> List of Departments
                    </h5>
                    {{-- Optionally add an Add Department button here --}}
                    {{--
                    <a href="{{ route('departments.create') }}"
                        class="btn btn-success btn-sm rounded-pill shadow-sm">
                        <i class="fa-solid fa-plus"></i> Add Department
                    </a>
                    --}}
                </div>
                <div class="card-body bg-light rounded-bottom-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive rounded-4">
                        <table class="table align-middle mb-0 table-hover small">
                            <thead class="table-info">
                                <tr>
                                    <th class="text-center text-black" style="width: 6%;">#</th>
                                    <th class="text-black">Department Name</th>
                                    <th class="text-black">Department Code</th>
                                    <th class="text-center text-black" style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)
                                    <tr>
                                        <td class="text-center text-black fw-bold">{{ $loop->iteration }}</td>
                                        <td class="text-black fw-semibold">{{ $department->name }}</td>
                                        <td class="text-black fw-semibold">{{ $department->code ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <!-- Edit Modal Trigger (implement modal if needed) -->
                                            <a href="#"
                                                class="btn btn-outline-warning btn-sm rounded-circle shadow-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDepartmentModal-{{ $department->id }}"
                                                title="Edit Department">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <!-- Delete -->
                                            <form action="{{ route('departments.destroy', $department->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                                    onclick="return confirm('Are you sure?')" title="Delete Department">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Edit Department Modals (implement partial if needed) --}}
                    @foreach ($departments as $department)
                        @include('admin.layouts.modal.editdepartment', ['department' => $department])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
