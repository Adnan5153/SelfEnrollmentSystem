@extends('layouts.admin')

@section('content')

    <!-- Card Container -->
    <div class="bg-white bg-opacity-75 my-5 p-5 rounded-4 shadow-lg border-0 mx-auto" style="max-width:900px;">

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fa-solid fa-circle-exclamation me-1"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Header -->
        <div class="text-center mb-4">
            <div class="mb-2">
                <span
                    class="bg-primary bg-opacity-10 text-primary fs-3 rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width:60px; height:60px;">
                    <i class="fa-solid fa-award"></i>
                </span>
            </div>
            <h2 class="fw-bold mb-1 text-dark">Add Grade</h2>
            <div class="text-secondary">Fill in the details below to add a new grade</div>
        </div>

        <!-- Form Start -->
        <form action="{{ route('grades.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-4">
                <h5 class="fw-semibold text-dark mb-3">
                    <i class="fa-solid fa-list-check me-2 text-primary"></i>Grade Information
                </h5>
                <div class="row g-3 align-items-end">
                    <!-- Minimum Marks -->
                    <div class="col-md-3">
                        <label for="min_marks" class="form-label text-secondary">Minimum Marks</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-arrow-down-1-9"></i>
                            </span>
                            <input type="number" class="form-control form-control-lg shadow-sm rounded-3"
                                id="min_marks" name="min_marks" min="0" required>
                        </div>
                    </div>
                    <!-- Maximum Marks -->
                    <div class="col-md-3">
                        <label for="max_marks" class="form-label text-secondary">Maximum Marks</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-arrow-up-1-9"></i>
                            </span>
                            <input type="number" class="form-control form-control-lg shadow-sm rounded-3"
                                id="max_marks" name="max_marks" min="0" required>
                        </div>
                    </div>
                    <!-- Grade -->
                    <div class="col-md-3">
                        <label for="grade" class="form-label text-secondary">Grade</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-font"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg shadow-sm rounded-3"
                                id="grade" name="grade" maxlength="2" required>
                        </div>
                    </div>
                    <!-- Remarks -->
                    <div class="col-md-3">
                        <label for="remarks" class="form-label text-secondary">
                            Remarks <span class="fw-light text-muted">(Optional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-comment"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg shadow-sm rounded-3"
                                id="remarks" name="remarks">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit/Reset Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit"
                        class="btn btn-primary rounded-pill px-4 py-2 d-flex align-items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-plus"></i> <span>Submit</span>
                </button>
                <button type="reset"
                        class="btn btn-outline-secondary rounded-pill px-4 py-2 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> <span>Reset</span>
                </button>
            </div>
        </form>
    </div>
@endsection
