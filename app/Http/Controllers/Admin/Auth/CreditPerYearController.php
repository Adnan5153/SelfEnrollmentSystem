<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditPerYear;
use App\Models\Department;

class CreditPerYearController extends Controller
{
    public function index()
    {
        $items = CreditPerYear::with('department')->orderBy('department_id')->orderBy('year')->get();
        $departments = Department::all();
        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year', 'Technical Electives'];
        return view('admin.layouts.credit_per_year_index', compact('items', 'departments', 'years'));
    }

    public function create()
    {
        $departments = Department::all();
        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year', 'Technical Electives'];
        return view('admin.layouts.credit_per_year_set', compact('departments', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'year' => 'required|string',
            'required_credits' => 'required|string|max:255',
        ]);

        CreditPerYear::updateOrCreate(
            ['department_id' => $validated['department_id'], 'year' => $validated['year']],
            ['required_credits' => $validated['required_credits']]
        );

        return redirect()->route('creditperyear.index')->with('success', 'Credit requirement saved.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'year' => 'required|string',
            'required_credits' => 'required|string|max:255',
        ]);

        // Enforce uniqueness of (department_id, year) excluding this record
        $exists = CreditPerYear::where('department_id', $validated['department_id'])
            ->where('year', $validated['year'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['year' => 'A record for this department and year already exists.'])->withInput();
        }

        $item = CreditPerYear::findOrFail($id);
        $item->update([
            'department_id' => $validated['department_id'],
            'year' => $validated['year'],
            'required_credits' => $validated['required_credits'],
        ]);

        return redirect()->route('creditperyear.index')->with('success', 'Credit requirement updated.');
    }

    public function destroy($id)
    {
        $item = CreditPerYear::findOrFail($id);
        $item->delete();
        return redirect()->route('creditperyear.index')->with('success', 'Credit requirement deleted.');
    }
}
