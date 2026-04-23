@extends('layouts.admin')

@section('content')
<div class="container-fluid my-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0 text-center text-md-start">
                        Course Distribution - Credit Requirements by Department
                    </h3>
                </div>

                <div class="card-body">
                    @foreach ($departments as $department)
                        <div class="bg-light p-3 p-md-4 rounded mb-4 border-start border-4 
                            {{ $department->code === 'CSE' ? 'border-primary' : 'border-success' }}">
                            <h4 class="text-primary mb-3 d-flex align-items-center flex-wrap">
                                <i class="fas fa-{{ $department->code === 'CSE' ? 'laptop-code' : 'bolt' }} me-2"></i>
                                <span>{{ $department->name }} ({{ $department->code }})</span>
                            </h4>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead class="table-{{ $department->code === 'CSE' ? 'dark' : 'success' }}">
                                        <tr>
                                            <th>Year</th>
                                            <th>Required Credits</th>
                                            <th>Total Credits</th>
                                            <th>Description</th>
                                            <th>Subjects Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courseDistributions[$department->code]['regular'] as $distribution)
                                            <tr>
                                                <td><strong>{{ $distribution['year'] }}</strong></td>
                                                <td>{{ $distribution['required_credits'] }} Credits</td>
                                                <td>{{ $distribution['total_credits'] }}</td>
                                                <td>{{ $distribution['description'] }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $distribution['subjects_count'] }} subjects
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if (isset($courseDistributions[$department->code]['technical_elective']))
                                            <tr class="table-info">
                                                <td><strong>{{ $courseDistributions[$department->code]['technical_elective']['year'] }}</strong></td>
                                                <td>{{ $courseDistributions[$department->code]['technical_elective']['required_credits'] }} Credits</td>
                                                <td>{{ $courseDistributions[$department->code]['technical_elective']['total_credits'] }}</td>
                                                <td>{{ $courseDistributions[$department->code]['technical_elective']['description'] }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $courseDistributions[$department->code]['technical_elective']['subjects_count'] }} subjects
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Final Year Options -->
                            <div class="mt-3">
                                <h6 class="text-success mb-3">Final Year Options:</h6>
                                <div class="row g-3">
                                    @if (isset($courseDistributions[$department->code]['thesis']))
                                        <div class="col-12 col-md-6">
                                            <div class="card border-success h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title text-success mb-2">
                                                        <i class="fas fa-graduation-cap me-2"></i>Thesis Option
                                                    </h6>
                                                    <p class="card-text small mb-0">
                                                        <strong>Final Year Thesis:</strong>
                                                        {{ $courseDistributions[$department->code]['thesis']['required_credits'] }} Credits<br>
                                                        <strong>Additional Technical Electives:</strong> 0 Credits<br>
                                                        <strong>Total:</strong>
                                                        {{ $courseDistributions[$department->code]['thesis']['total_credits'] }} Credits
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($courseDistributions[$department->code]['internship']))
                                        <div class="col-12 col-md-6">
                                            <div class="card border-warning h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title text-warning mb-2">
                                                        <i class="fas fa-briefcase me-2"></i>Internship Option
                                                    </h6>
                                                    <p class="card-text small mb-0">
                                                        <strong>Internship:</strong>
                                                        {{ $courseDistributions[$department->code]['internship']['required_credits'] }} Credits<br>
                                                        <strong>Additional Technical Electives:</strong> 3 Credits<br>
                                                        <strong>Total:</strong>
                                                        {{ $courseDistributions[$department->code]['internship']['total_credits'] + 3 }} Credits
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Summary Section -->
                    <div class="mt-5">
                        <div class="card border-primary shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0 d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>Credit Distribution Summary
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-12 col-md-6">
                                        <h6 class="text-primary">How Credits Are Calculated:</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li><i class="fas fa-database text-info me-2"></i>Credits pulled from subjects table</li>
                                            <li><i class="fas fa-calculator text-success me-2"></i>Each subject's credit_hour is summed</li>
                                            <li><i class="fas fa-filter text-warning me-2"></i>Filtered by department and year</li>
                                            <li><i class="fas fa-sync text-primary me-2"></i>Updates automatically when subjects change</li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <h6 class="text-primary">Credit Structure:</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li><i class="fas fa-graduation-cap text-success me-2"></i>Regular Years: 1st → 2nd → 3rd → 4th</li>
                                            <li><i class="fas fa-star text-warning me-2"></i>Technical Electives: Optional courses</li>
                                            <li><i class="fas fa-book text-info me-2"></i>Thesis: +6 credits (fixed)</li>
                                            <li><i class="fas fa-briefcase text-primary me-2"></i>Internship: +3 credits (fixed)</li>
                                        </ul>
                                    </div>
                                </div>
                                <hr class="my-3">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>Note:</strong> All credit calculations are performed in real time from your current subject database. To modify credits, add or remove subjects or change their values in the subject management section.
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div>
    </div>
</div>
@endsection
