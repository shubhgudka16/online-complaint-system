<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $departments = Department::withCount('complaints')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = Department::create($request->validated());

        return redirect()->route('departments.index')
            ->with('success', "Department '{$department->name}' created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department): RedirectResponse
    {
        return redirect()->route('departments.edit', $department);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('departments.index')
            ->with('success', "Department '{$department->name}' updated successfully.");
    }

    /**
     * Remove the specified resource from storage safely.
     */
    public function destroy(Department $department): RedirectResponse
    {
        $complaintCount = $department->complaints()->count();

        if ($complaintCount > 0) {
            return redirect()->route('departments.index')
                ->with('error', "Cannot delete department '{$department->name}' because it has {$complaintCount} associated complaint(s). Please reassign or resolve those complaints first.");
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', "Department '{$department->name}' deleted successfully.");
    }
}
