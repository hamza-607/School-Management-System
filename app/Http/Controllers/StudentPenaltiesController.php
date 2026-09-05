<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Student_penalties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPenaltiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $studentID)
    {
        // dd($request->all());
        $query = Student_penalties::query();
        $query->where('student_id', $studentID);

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('reason', 'like', '%' . $request->search . '%')
                    ->orWhere('notes', 'like', '%' . $request->search . '%')
                    ->orWhere('penalty_type', 'like', '%' . $request->search . '%');
            });
        }

        $penalties = $query->latest()->paginate($request->per_page ?? 10)->withQueryString();
        return view('students.penalties.index', [
            'penalties' => $penalties,
            'theStudent' => Student::findOrFail($studentID),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($studentID)
    {
        return view('students.penalties.create', [
            'theStudent' => Student::findOrFail($studentID),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $studentID)
    {
        try {
            $validated = $request->validate([
                'penalty_type' => 'required|string|max:100',
                'reason' => 'required|string|max:1000',
                'notes' => 'nullable|string|max:1000',
            ]);

            Student_penalties::create([
                'penalty_type' => $validated['penalty_type'],
                'reason' => $validated['reason'],
                'user_id' => Auth::id(),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? '-',
                'student_id' => $studentID,
            ]);

            return redirect()->route('penalties.index', $studentID)->with('success', 'تم إضافة العقوبة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('penalties.index', $studentID)->with('error', 'حدث خطأ أثناء إضافة العقوبة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, $studentID)
    {
        return view('students.penalties.show', [
            'thepenalty' => Student_penalties::findOrFail($id),
            'theStudent' => Student::findOrFail($studentID),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, $studentID)
    {
        return view('students.penalties.edit', [
            'thepenalty' => Student_penalties::findOrFail($id),
            'theStudent' => Student::findOrFail($studentID),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, $studentID)
    {
        try {
            $validated = $request->validate([
                'penalty_type' => 'required|string|max:100',
                'reason' => 'required|string|max:1000',
                'notes' => 'nullable|string|max:1000',
            ]);

            $penalty = Student_penalties::findOrFail($id);
            $penalty->update([
                'penalty_type' => $validated['penalty_type'],
                'reason' => $validated['reason'],
                'updated_by' => Auth::id(),
                'notes' => $validated['notes'] ?? '-',
            ]);

            return redirect()->route('penalties.index', $studentID)->with('success', 'تم إضافة العقوبة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('penalties.index', $studentID)->with('error', 'حدث خطأ أثناء إضافة العقوبة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, $studentID)
    {
        try {
            $penalty = Student_penalties::findOrFail($id);
            $penalty->delete();

            return redirect()->route('penalties.index', $studentID)->with('success', 'تم حذف العقوبة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('penalties.index', $studentID)->with('error', 'حدث خطأ أثناء حذف العقوبة: ' . $e->getMessage());
        }
    }

    public function penaltyStatusToggel($penaltyID, Request $request)
    {
        try {
            $penalty = Student_penalties::findOrFail($penaltyID);
            $newStatus = $request->penaltyStatus === 'ملغية' ? 'canceled' : 'applied';
            // dd($newStatus);

            $penalty->update([
                'status' => $newStatus,
                'updated_by' => Auth::id(),
            ]);


            return redirect(url()->previous())->with('success', 'تم تحديث حالة العقوبة بنجاح.');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تحديث حالة العقوبة: ' . $e->getMessage());
        }
    }
}
