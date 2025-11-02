@extends('layouts.admin')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex flex-wrap align-items-center gap-2 px-4 py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list"></i> List of Classes
                    </h5>
                    {{-- Optionally add an Add Class button here --}}
                    {{--
                    <a href="{{ route('classes.create') }}"
                        class="btn btn-success btn-sm rounded-pill shadow-sm">
                        <i class="fa-solid fa-plus"></i> Add Class
                    </a>
                    --}}
                </div>
                <div class="card-body bg-light rounded-bottom-4">
                    @if(session('success'))
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
                                    <th class="text-black">Class Name</th>
                                    <th class="text-black">Section</th>
                                    <th class="text-black">Capacity</th>
                                    <th class="text-center text-black" style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classes as $class)
                                    <tr>
                                        <td class="text-center text-black fw-bold">{{ $loop->iteration }}</td>
                                        <td class="text-black fw-semibold">{{ $class->class_name }}</td>
                                        <td class="text-black fw-semibold">{{ $class->section }}</td>
                                        <td class="text-black fw-semibold">{{ $class->capacity }}</td>
                                        <td class="text-center">
                                            <!-- Edit Modal Trigger -->
                                            <button type="button"
                                                class="btn btn-outline-warning btn-sm rounded-circle shadow-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editClassModal-{{ $class->id }}"
                                                title="Edit Class">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <!-- Delete -->
                                            <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                                        onclick="return confirm('Are you sure?')"
                                                        title="Delete Class">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (Uncomment if needed) --}}
                    {{--
                    <div class="d-flex justify-content-center mt-4">
                        {{ $classes->links() }}
                    </div>
                    --}}

                    {{-- Edit Class Modals --}}
                    @foreach($classes as $class)
                        @include('admin.layouts.modal.editclass', ['class' => $class])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
