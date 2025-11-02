@extends('layouts.admin')

@section('content')
    <div class="row mt-5 justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div
                    class="card-header bg-dark bg-gradient text-white rounded-top-4 d-flex flex-wrap align-items-center gap-2 px-4 py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list"></i> Credit Requirements
                    </h5>
                    <a href="{{ route('creditperyear.create') }}"
                        class="btn btn-success btn-sm rounded-pill shadow-sm ms-auto">
                        <i class="fa-solid fa-plus"></i> Set Credit
                    </a>
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
                                    <th class="text-black">Department</th>
                                    <th class="text-black">Year</th>
                                    <th class="text-black">Required Credits</th>
                                    <th class="text-center text-black" style="width: 16%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $i => $row)
                                    <tr>
                                        <td class="text-center text-black fw-bold">{{ $i + 1 }}</td>
                                        <td class="text-black fw-semibold">{{ $row->department->name ?? 'N/A' }}
                                            ({{ $row->department->code ?? '' }})
                                        </td>
                                        <td class="text-black fw-semibold">{{ $row->year }}</td>
                                        <td class="text-black">{{ $row->required_credits }}</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-outline-warning btn-sm rounded-circle shadow-sm me-1"
                                                data-bs-toggle="modal" data-bs-target="#editCreditReq-{{ $row->id }}"
                                                title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <form action="{{ route('creditperyear.destroy', $row->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                                    title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('admin.layouts.modal.editcreditperyear', [
                                        'row' => $row,
                                        'departments' => $departments,
                                        'years' => $years,
                                    ])
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No records yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
