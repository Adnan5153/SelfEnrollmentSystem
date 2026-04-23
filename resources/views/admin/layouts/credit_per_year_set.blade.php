@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-dark bg-gradient text-white rounded-top-4">
            <h5 class="mb-0"><i class="fa-solid fa-gear me-2"></i>Set Credit Requirement</h5>
        </div>

        <div class="card-body bg-light rounded-bottom-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('creditperyear.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold">Department</label>
                        <select name="department_id" class="form-select" required>
                            <option value="" disabled selected>Select</option>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold">Year</label>
                        <select name="year" class="form-select" required>
                            <option value="" disabled selected>Select</option>
                            @foreach ($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold">Required Credits</label>
                        <input type="number" name="required_credits" min="0" step="0.1"
                               class="form-control" placeholder="Enter credits" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
