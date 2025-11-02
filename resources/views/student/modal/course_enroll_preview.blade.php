<!-- resources/views/student/modal/course_enroll_preview.blade.php -->
<div class="modal fade" id="courseEnrollPreviewModal" tabindex="-1" aria-labelledby="enrollPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title" id="enrollPreviewLabel">
                    <i class="fa-solid fa-list-check me-2"></i>
                    Course Enrollment Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Timing</th>
                                <th>Teacher</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody id="preview-course-table-body">
                            <!-- Dynamically filled by JS -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total Credits</th>
                                <th id="preview-course-total-credits" class="fw-bold text-success">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="enroll-final-submit-btn" type="button" class="btn btn-success rounded-pill px-4">
                    <i class="fa-solid fa-check"></i> Confirm & Enroll
                </button>
            </div>
        </div>
    </div>
</div>
