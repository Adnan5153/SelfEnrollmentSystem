<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;

class CourseDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all departments
        $departments = Department::all();

        // Get course distributions grouped by department
        $courseDistributions = [];
        foreach ($departments as $department) {
            $courseDistributions[$department->code] = [
                'regular' => $this->getRegularYearsData($department->code),
                'technical_elective' => $this->getTechnicalElectiveData($department->code),
                'thesis' => $this->getThesisData($department->code),
                'internship' => $this->getInternshipData($department->code),
            ];
        }

        return view('admin.layouts.coursedistribution', compact('courseDistributions', 'departments'));
    }

    /**
     * Get regular years data for a department
     */
    private function getRegularYearsData($departmentCode)
    {
        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $data = [];

        foreach ($years as $year) {
            $subjects = Subject::where('year', $year)
                ->whereHas('department', function ($query) use ($departmentCode) {
                    $query->where('code', $departmentCode);
                })
                ->with('credit')
                ->get();

            $totalCredits = $subjects->sum('credit.credit_hour');
            $cumulativeCredits = $this->calculateCumulativeCredits($departmentCode, $year);

            $data[] = [
                'year' => $year,
                'required_credits' => $totalCredits,
                'total_credits' => $cumulativeCredits,
                'description' => $this->getYearDescription($year),
                'subjects_count' => $subjects->count()
            ];
        }

        return $data;
    }

    /**
     * Get technical elective data for a department
     */
    private function getTechnicalElectiveData($departmentCode)
    {
        $subjects = Subject::where('year', 'Technical Electives')
            ->whereHas('department', function ($query) use ($departmentCode) {
                $query->where('code', $departmentCode);
            })
            ->with('credit')
            ->get();

        $totalCredits = $subjects->sum('credit.credit_hour');
        $regularCredits = $this->calculateRegularYearsCredits($departmentCode);

        return [
            'year' => 'Technical Electives',
            'required_credits' => $totalCredits,
            'total_credits' => $regularCredits + $totalCredits,
            'description' => 'Optional courses to reach minimum requirement',
            'subjects_count' => $subjects->count()
        ];
    }

    /**
     * Get thesis data for a department
     */
    private function getThesisData($departmentCode)
    {
        $regularCredits = $this->calculateRegularYearsCredits($departmentCode);
        $techElectiveCredits = $this->getTechnicalElectiveData($departmentCode)['required_credits'];

        return [
            'year' => 'Thesis Option',
            'required_credits' => 6,
            'total_credits' => $regularCredits + $techElectiveCredits + 6,
            'description' => 'Final Year Thesis',
            'subjects_count' => 0
        ];
    }

    /**
     * Get internship data for a department
     */
    private function getInternshipData($departmentCode)
    {
        $regularCredits = $this->calculateRegularYearsCredits($departmentCode);
        $techElectiveCredits = $this->getTechnicalElectiveData($departmentCode)['required_credits'];

        return [
            'year' => 'Internship Option',
            'required_credits' => 3,
            'total_credits' => $regularCredits + $techElectiveCredits + 3,
            'description' => 'Internship + Additional Technical Electives',
            'subjects_count' => 0
        ];
    }

    /**
     * Calculate cumulative credits up to a specific year
     */
    private function calculateCumulativeCredits($departmentCode, $targetYear)
    {
        $yearOrder = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $targetIndex = array_search($targetYear, $yearOrder);
        $totalCredits = 0;

        for ($i = 0; $i <= $targetIndex; $i++) {
            $year = $yearOrder[$i];
            $subjects = Subject::where('year', $year)
                ->whereHas('department', function ($query) use ($departmentCode) {
                    $query->where('code', $departmentCode);
                })
                ->with('credit')
                ->get();

            $totalCredits += $subjects->sum('credit.credit_hour');
        }

        return $totalCredits;
    }

    /**
     * Calculate total credits for regular years only
     */
    private function calculateRegularYearsCredits($departmentCode)
    {
        $regularYears = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $totalCredits = 0;

        foreach ($regularYears as $year) {
            $subjects = Subject::where('year', $year)
                ->whereHas('department', function ($query) use ($departmentCode) {
                    $query->where('code', $departmentCode);
                })
                ->with('credit')
                ->get();

            $totalCredits += $subjects->sum('credit.credit_hour');
        }

        return $totalCredits;
    }

    /**
     * Get description for each year
     */
    private function getYearDescription($year)
    {
        $descriptions = [
            '1st Year' => 'Foundation courses, Mathematics, Physics',
            '2nd Year' => 'Core programming, Data structures, Algorithms',
            '3rd Year' => 'Advanced programming, Software engineering',
            '4th Year' => 'Specialization, Project work'
        ];

        return $descriptions[$year] ?? 'Core subjects for this year';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
