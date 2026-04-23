@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex flex-wrap align-items-center gap-2 px-3 py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list"></i> Credit Requirements
                    </h5>
                    <a href="{{ route('creditperyear.create') }}" class="btn btn-success btn-sm rounded-pill shadow-sm ms-auto">
                        <i class="fa-solid fa-plus"></i> <span class="d-none d-sm-inline">Set Credit</span>
                    </a>
                </div>

                <div class="card-body bg-light rounded-bottom-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0 small">
                            <thead class="table-info">
                                <tr>
                                    <th class="text-center text-black">#</th>
                                    <th class="text-black">Department</th>
                                    <th class="text-black">Year</th>
                                    <th class="text-black">Required Credits</th>
                                    <th class="text-center text-black">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $i => $row)
                                    <tr>
                                        <td class="text-center fw-bold text-black">{{ $i + 1 }}</td>
                                        <td class="fw-semibold text-black">
                                            {{ $row->department->name ?? 'N/A' }}
                                            <span class="text-muted small">({{ $row->department->code ?? '' }})</span>
                                        </td>
                                        <td class="fw-semibold text-black">{{ $row->year }}</td>
                                        <td class="text-black">{{ $row->required_credits }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center flex-wrap gap-1">
                                                <button type="button"
                                                    class="btn btn-outline-warning btn-sm rounded-circle shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCreditReq-{{ $row->id }}"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>

                                                <form action="{{ route('creditperyear.destroy', $row->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                                        title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    @include('admin.layouts.modal.editcreditperyear', [
                                        'row' => $row,
                                        'departments' => $departments,
                                        'years' => $years,
                                    ])
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No records yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
