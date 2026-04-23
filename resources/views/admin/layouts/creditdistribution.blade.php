@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="row mt-4 justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark bg-gradient text-white d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2 flex-grow-1 flex-sm-grow-0 text-center text-sm-start">
                        <i class="fa-solid fa-book"></i> Credit Distribution
                    </h5>
                    <a href="#" class="btn btn-success btn-sm rounded-pill shadow-sm ms-auto" data-bs-toggle="modal" data-bs-target="#addCreditModal">
                        <i class="fa-solid fa-plus"></i>
                        <span class="d-none d-md-inline">Add Credit</span>
                    </a>
                </div>

                <div class="card-body bg-light">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-info">
                                <tr>
                                    <th class="text-center" style="width: 6%;">#</th>
                                    <th class="text-center">Subject Type</th>
                                    <th class="text-center">Credit Hour</th>
                                    <th class="text-center" style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($credits as $credit)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td class="text-center fw-semibold text-capitalize">{{ $credit->subject_type }}</td>
                                        <td class="text-center fw-semibold">{{ $credit->credit_hour }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center flex-wrap gap-1">
                                                <button type="button" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCreditModal-{{ $credit->id }}"
                                                    title="Edit Credit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <form action="{{ route('creditdistribution.destroy', $credit->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle shadow-sm"
                                                        onclick="return confirm('Are you sure you want to delete this credit?')"
                                                        title="Delete Credit">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No credit distributions available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $credits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Credit Modal -->
<div class="modal fade" id="addCreditModal" tabindex="-1" aria-labelledby="addCreditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('creditdistribution.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                    <h6 class="modal-title">
                        <i class="fa-solid fa-plus"></i> Add Credit
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label for="subject_type" class="form-label small">Subject Type</label>
                        <select class="form-select form-select-sm rounded-3 shadow-sm" id="subject_type" name="subject_type" required>
                            <option value="" selected disabled>Select subject type</option>
                            <option value="theory">Theory</option>
                            <option value="lab">Lab</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="credit_hour" class="form-label small">Credit Hour</label>
                        <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                            id="credit_hour" name="credit_hour" placeholder="Enter credit hour" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-save"></i> Add Credit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Credit Modals -->
@foreach ($credits as $credit)
<div class="modal fade" id="editCreditModal-{{ $credit->id }}" tabindex="-1" aria-labelledby="editCreditModalLabel-{{ $credit->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('creditdistribution.update', $credit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-dark bg-gradient text-white rounded-top-4 py-2 px-3">
                    <h6 class="modal-title">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Credit
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label for="subject_type-{{ $credit->id }}" class="form-label small">Subject Type</label>
                        <select class="form-select form-select-sm rounded-3 shadow-sm"
                            id="subject_type-{{ $credit->id }}" name="subject_type" required>
                            <option value="theory" {{ $credit->subject_type == 'theory' ? 'selected' : '' }}>Theory</option>
                            <option value="lab" {{ $credit->subject_type == 'lab' ? 'selected' : '' }}>Lab</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="credit_hour-{{ $credit->id }}" class="form-label small">Credit Hour</label>
                        <input type="text" class="form-control form-control-sm rounded-3 shadow-sm"
                            id="credit_hour-{{ $credit->id }}" name="credit_hour" value="{{ $credit->credit_hour }}" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-end gap-2 py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-save"></i> Update Credit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
