<?php

namespace App\Http\Controllers;

use App\Http\Requests\Complaint\StoreComplaintRequest;
use App\Http\Requests\Complaint\UpdateComplaintRequest;
use App\Http\Requests\Complaint\UpdateStatusRequest;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = $user->isAdmin()
            ? Complaint::with(['user', 'department'])
            : $user->complaints()->with('department');

        $complaints = $query->filter($request->only(['search', 'status', 'department_id', 'priority']))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();
        $priorities = Complaint::PRIORITIES;
        $statuses = Complaint::STATUSES;

        return view('complaints.index', compact('complaints', 'departments', 'priorities', 'statuses'));
    }

    /**
     * Show the form for creating a new complaint.
     */
    public function create(): View
    {
        Gate::authorize('create', Complaint::class);

        $departments = Department::orderBy('name')->get();
        $priorities = Complaint::PRIORITIES;

        return view('complaints.create', compact('departments', 'priorities'));
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(StoreComplaintRequest $request): RedirectResponse
    {
        Gate::authorize('create', Complaint::class);

        $complaint = Complaint::create([
            'reference_no' => Complaint::generateReferenceNo(),
            'user_id' => $request->user()->id,
            'department_id' => $request->integer('department_id'),
            'title' => $request->string('title'),
            'description' => $request->string('description'),
            'priority' => $request->string('priority'),
            'status' => 'Pending',
            'admin_remarks' => null,
        ]);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', "Complaint submitted successfully! Your tracking ticket is {$complaint->reference_no}.");
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint): View
    {
        Gate::authorize('view', $complaint);

        $complaint->load(['user', 'department']);
        $statuses = Complaint::STATUSES;

        return view('complaints.show', compact('complaint', 'statuses'));
    }

    /**
     * Show the form for editing the specified complaint.
     */
    public function edit(Complaint $complaint): View
    {
        Gate::authorize('update', $complaint);

        $departments = Department::orderBy('name')->get();
        $priorities = Complaint::PRIORITIES;

        return view('complaints.edit', compact('complaint', 'departments', 'priorities'));
    }

    /**
     * Update the specified complaint in storage.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint): RedirectResponse
    {
        Gate::authorize('update', $complaint);

        $complaint->update($request->validated());

        return redirect()->route('complaints.show', $complaint)
            ->with('success', "Complaint {$complaint->reference_no} updated successfully.");
    }

    /**
     * Remove the specified complaint from storage.
     */
    public function destroy(Complaint $complaint): RedirectResponse
    {
        Gate::authorize('delete', $complaint);

        $ref = $complaint->reference_no;
        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', "Complaint {$ref} deleted successfully.");
    }

    /**
     * Update complaint status and administrative remarks (Admin only).
     */
    public function updateStatus(UpdateStatusRequest $request, Complaint $complaint): RedirectResponse
    {
        $complaint->update($request->validated());

        return redirect()->route('complaints.show', $complaint)
            ->with('success', "Status for {$complaint->reference_no} updated to '{$complaint->status}'.");
    }
}
