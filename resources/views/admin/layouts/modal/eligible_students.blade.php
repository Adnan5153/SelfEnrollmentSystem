<!-- Eligible Students Modal -->
<div class="modal fade" id="eligibleStudentsModal-{{ $course['id'] }}" tabindex="-1"
    aria-labelledby="eligibleStudentsModalLabel-{{ $course['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="eligibleStudentsModalLabel-{{ $course['id'] }}">
                    Eligible Students - {{ $course['name'] ?? 'Course' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Summary Cards -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <strong>Credit Eligible:</strong> <span
                                id="creditEligibleCount">{{ $course['eligible'] ?? 0 }}</span> students
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            <strong>Prerequisites Met:</strong> <span
                                id="prereqMetCount">{{ $course['prereq_met'] ?? 0 }}</span> students
                        </div>
                    </div>
                </div>

                <!-- Pagination Controls -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="rowsPerPage">
                            <option value="25">25 per page</option>
                            <option value="50" selected>50 per page</option>
                            <option value="100">100 per page</option>
                            <option value="200">200 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Students with Credit Eligibility Only -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-warning mb-0">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            Credit Eligible (Prerequisites Pending)
                        </h6>
                        <small class="text-muted">
                            Showing <span id="creditEligibleShowing">0</span> of <span id="creditEligibleTotal">0</span>
                            students
                        </small>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-warning sticky-top">
                                <tr>
                                    <th style="width: 15%;">ID</th>
                                    <th style="width: 35%;">Name</th>
                                    <th style="width: 20%;">Year</th>
                                    <th style="width: 20%;">Credits</th>
                                    <th style="width: 10%;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="creditEligibleTableBody">
                                @if (isset($course['credit_eligible_students']) &&
                                        is_array($course['credit_eligible_students']) &&
                                        count($course['credit_eligible_students']) > 0)
                                    @foreach ($course['credit_eligible_students'] as $student)
                                        <tr class="student-row" data-id="{{ $student['id'] ?? '' }}"
                                            data-name="{{ strtolower($student['name'] ?? '') }}"
                                            data-year="{{ $student['year'] ?? '' }}"
                                            data-credits="{{ $student['credit_completed'] ?? 0 }}">
                                            <td class="fw-bold">{{ $student['id'] ?? 'N/A' }}</td>
                                            <td>{{ $student['name'] ?? 'N/A' }}</td>
                                            <td><span class="badge bg-secondary">{{ $student['year'] ?? 'N/A' }}</span>
                                            </td>
                                            <td class="text-center">{{ $student['credit_completed'] ?? 0 }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Credit Eligible</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            <i class="fa-solid fa-info-circle me-2"></i>
                                            {{ ($course['eligible'] ?? 0) - ($course['prereq_met'] ?? 0) }} students
                                            meet credit requirements but need prerequisite completion
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination for Credit Eligible -->
                    <nav aria-label="Credit eligible pagination" class="mt-2">
                        <ul class="pagination pagination-sm justify-content-center" id="creditEligiblePagination">
                            <!-- Pagination will be generated by JavaScript -->
                        </ul>
                    </nav>
                </div>

                <!-- Students with Both Credit and Prerequisite Eligibility -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-success mb-0">
                            <i class="fa-solid fa-check-double me-2"></i>
                            Fully Eligible (Credits + Prerequisites)
                        </h6>
                        <small class="text-muted">
                            Showing <span id="fullyEligibleShowing">0</span> of <span id="fullyEligibleTotal">0</span>
                            students
                        </small>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-success sticky-top">
                                <tr>
                                    <th style="width: 15%;">ID</th>
                                    <th style="width: 35%;">Name</th>
                                    <th style="width: 20%;">Year</th>
                                    <th style="width: 20%;">Credits</th>
                                    <th style="width: 10%;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="fullyEligibleTableBody">
                                @if (isset($course['fully_eligible_students']) &&
                                        is_array($course['fully_eligible_students']) &&
                                        count($course['fully_eligible_students']) > 0)
                                    @foreach ($course['fully_eligible_students'] as $student)
                                        <tr class="student-row" data-id="{{ $student['id'] ?? '' }}"
                                            data-name="{{ strtolower($student['name'] ?? '') }}"
                                            data-year="{{ $student['year'] ?? '' }}"
                                            data-credits="{{ $student['credit_completed'] ?? 0 }}">
                                            <td class="fw-bold">{{ $student['id'] ?? 'N/A' }}</td>
                                            <td>{{ $student['name'] ?? 'N/A' }}</td>
                                            <td><span class="badge bg-secondary">{{ $student['year'] ?? 'N/A' }}</span>
                                            </td>
                                            <td class="text-center">{{ $student['credit_completed'] ?? 0 }}</td>
                                            <td>
                                                <span class="badge bg-success">Fully Eligible</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            <i class="fa-solid fa-info-circle me-2"></i>
                                            {{ $course['prereq_met'] ?? 0 }} students are fully eligible (meet both
                                            credit and prerequisite requirements)
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination for Fully Eligible -->
                    <nav aria-label="Fully eligible pagination" class="mt-2">
                        <ul class="pagination pagination-sm justify-content-center" id="fullyEligiblePagination">
                            <!-- Pagination will be generated by JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('eligibleStudentsModal-{{ $course['id'] }}');
        if (!modal) return;

        // Configuration
        let currentPage = 1;
        let rowsPerPage = 50;
        let allCreditEligibleStudents = [];
        let allFullyEligibleStudents = [];

        // Initialize data
        function initializeData() {
            // Get all student rows
            const creditRows = modal.querySelectorAll('#creditEligibleTableBody .student-row');
            const fullyRows = modal.querySelectorAll('#fullyEligibleTableBody .student-row');

            console.log('Found credit rows:', creditRows.length);
            console.log('Found fully rows:', fullyRows.length);

            allCreditEligibleStudents = Array.from(creditRows).map(row => ({
                element: row,
                id: row.dataset.id,
                name: row.dataset.name,
                year: row.dataset.year,
                credits: parseInt(row.dataset.credits) || 0
            }));

            allFullyEligibleStudents = Array.from(fullyRows).map(row => ({
                element: row,
                id: row.dataset.id,
                name: row.dataset.name,
                year: row.dataset.year,
                credits: parseInt(row.dataset.credits) || 0
            }));

            // Show all rows initially
            creditRows.forEach(row => row.style.display = '');
            fullyRows.forEach(row => row.style.display = '');

            // If no rows found, show the "no data" message
            if (creditRows.length === 0) {
                const creditTableBody = modal.querySelector('#creditEligibleTableBody');
                if (creditTableBody) {
                    const noDataRow = creditTableBody.querySelector('tr:not(.student-row)');
                    if (noDataRow) noDataRow.style.display = '';
                }
            }

            if (fullyRows.length === 0) {
                const fullyTableBody = modal.querySelector('#fullyEligibleTableBody');
                if (fullyTableBody) {
                    const noDataRow = fullyTableBody.querySelector('tr:not(.student-row)');
                    if (noDataRow) noDataRow.style.display = '';
                }
            }
        }


        // Update display
        function updateDisplay() {
            displayStudents('creditEligible', allCreditEligibleStudents);
            displayStudents('fullyEligible', allFullyEligibleStudents);
            updateCounts();
        }

        // Display students with pagination
        function displayStudents(type, students) {
            const tableBody = document.getElementById(type + 'TableBody');
            const pagination = document.getElementById(type + 'Pagination');
            const showingSpan = document.getElementById(type + 'Showing');
            const totalSpan = document.getElementById(type + 'Total');

            if (!tableBody) return;

            // Hide all rows first (both student rows and no-data rows)
            const allRows = tableBody.querySelectorAll('tr');
            allRows.forEach(row => row.style.display = 'none');

            // Calculate pagination
            const totalStudents = students.length;
            const totalPages = Math.ceil(totalStudents / rowsPerPage);
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, totalStudents);

            if (totalStudents > 0) {
                // Show current page rows
                for (let i = startIndex; i < endIndex; i++) {
                    if (students[i] && students[i].element) {
                        students[i].element.style.display = '';
                    }
                }
            } else {
                // Show "no data" message
                const noDataRow = tableBody.querySelector('tr:not(.student-row)');
                if (noDataRow) noDataRow.style.display = '';
            }

            // Update counts
            if (showingSpan) showingSpan.textContent = totalStudents > 0 ? Math.min(rowsPerPage, totalStudents -
                startIndex) : 0;
            if (totalSpan) totalSpan.textContent = totalStudents;

            // Generate pagination
            generatePagination(pagination, totalPages, currentPage, type);
        }

        // Generate pagination
        function generatePagination(pagination, totalPages, currentPage, type) {
            if (!pagination) return;

            pagination.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML =
                `<a class="page-link" href="#" data-page="${currentPage - 1}" data-type="${type}">Previous</a>`;
            pagination.appendChild(prevLi);

            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#" data-page="${i}" data-type="${type}">${i}</a>`;
                pagination.appendChild(li);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML =
                `<a class="page-link" href="#" data-page="${currentPage + 1}" data-type="${type}">Next</a>`;
            pagination.appendChild(nextLi);
        }

        // Update counts
        function updateCounts() {
            const creditCount = document.getElementById('creditEligibleCount');
            const prereqCount = document.getElementById('prereqMetCount');

            if (creditCount) creditCount.textContent = allCreditEligibleStudents.length;
            if (prereqCount) prereqCount.textContent = allFullyEligibleStudents.length;
        }

        // Event listeners
        modal.addEventListener('shown.bs.modal', function() {
            console.log('Modal opened, initializing data...');
            initializeData();
            console.log('Credit eligible students:', allCreditEligibleStudents.length);
            console.log('Fully eligible students:', allFullyEligibleStudents.length);
            updateDisplay();
        });

        document.getElementById('rowsPerPage').addEventListener('change', function() {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            updateDisplay();
        });

        // Pagination click handler
        modal.addEventListener('click', function(e) {
            if (e.target.classList.contains('page-link')) {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page);
                const type = e.target.dataset.type;

                if (page && page !== currentPage) {
                    currentPage = page;
                    updateDisplay();
                }
            }
        });
    });
</script>
