<!-- resources/views/admin/layouts/modal/offer_to_class.blade.php -->
@if (isset($course))
    <div class="modal fade" id="offerToClassModal-{{ $course['id'] }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 shadow border-0">
                <div class="modal-header bg-primary text-white rounded-top-4 border-0">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Offer Course to Class</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courseoffering.offerToClass') }}" id="offerForm-{{ $course['id'] }}">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $course['id'] }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course Information</label>
                            <input type="text" class="form-control"
                                value="{{ $course['name'] ?? '' }} ({{ $course['code'] ?? '' }})" readonly>
                            <small class="text-muted">Credit: {{ $course['credit_hour'] ?? 0 }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="class_id_{{ $course['id'] }}" class="form-label fw-semibold">Select Class <span
                                    class="text-danger">*</span></label>
                            <select name="class_id" id="class_id_{{ $course['id'] }}" class="form-select" required>
                                <option value="" selected disabled>Choose a class...</option>
                                @foreach ($classes ?? [] as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">This will add the course to the selected class's offerings</small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="enforce_prereq" value="1"
                                id="enforce_prereq_{{ $course['id'] }}" checked>
                            <label class="form-check-label" for="enforce_prereq_{{ $course['id'] }}">
                                Enforce Prerequisites
                            </label>
                        </div>

                        <div class="alert alert-info mb-3">
                            <small>
                                <i class="fa-solid fa-info-circle me-1"></i>
                                After offering, the class will be able to access this course material.
                            </small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check me-1"></i>Offer Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form{{ $course['id'] }} = document.getElementById('offerForm-{{ $course['id'] }}');
            const modal{{ $course['id'] }} = document.getElementById('offerToClassModal-{{ $course['id'] }}');
            
            if (form{{ $course['id'] }}) {
                form{{ $course['id'] }}.addEventListener('submit', function(e) {
                    // Form will submit normally, modal will close on redirect
                    // Success message will show on the page after redirect
                });
            }
        });
    </script>
@endif
