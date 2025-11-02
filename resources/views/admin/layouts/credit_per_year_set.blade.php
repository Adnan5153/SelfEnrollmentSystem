@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4">Set Credit Requirement</div>
            <div class="card-body bg-light rounded-bottom-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('creditperyear.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="" disabled selected>Select</option>
                                @foreach ($departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" required>
                                <option value="" disabled selected>Select</option>
                                @foreach ($years as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Required Credits</label>
                            <input type="number" name="required_credits" min="0" step="0.1" class="form-control"
                                required />
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
