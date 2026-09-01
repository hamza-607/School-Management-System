<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSalaryAdjustments;
use App\Models\Staff;
use Illuminate\Http\Request;

class EmployeeSalaryAdjustmentsController extends Controller
{
    public function index($staffID, Request $request)
    {
        $staff = Staff::findOrFail($staffID);

        $employeeSalaryAdjustments = $staff->contract->salaryAdjustments;
        // dd($employeeSalaryAdjustments);

        return view('staff_members.employee_salary_adjustments.index', [
            'employeeSalaryAdjustments' => $employeeSalaryAdjustments,
            'staff' => $staff,
            'from' => $request->query('from') ? $request->query('from') : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($staffID, Request $request)
    {
        $staff = Staff::findOrFail($staffID);

        return view('staff_members.employee_salary_adjustments.create', [
            'from' => $request->query('from') ? $request->query('from') : null,
            'staff' => $staff,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($staffID, Request $request)
    {
        // dd($request->all());
        try {
            $validated = $request->validate([
                "type" => 'required|in:allowance,deduction',
                'amount_type' => 'required|in:fixed,percentage',
                'amount' => 'required|numeric|min:0',
                "reason" => 'nullable|string|max:1000',
            ]);

            $contractID = Staff::find($staffID)->contract->id;
            // dd($contractID);

            EmployeeSalaryAdjustments::create([
                'contract_id' => $contractID,
                'amount' => $validated['amount'],
                'amount_type' => $validated['amount_type'],
                'date' => now(),
                'reason' => $validated['reason'] ?? null,
                'type' => $validated['type'],
                'is_applied' => 0,
            ]);

            return redirect()->route('employee_salary_adjustments.index', ['staffID' => $staffID, 'from' => $request->query('from')])->with('success', 'تم إضافة العقد بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('employee_salary_adjustments.index', ['staffID' => $staffID, 'from' => $request->query('from')])->with('error', 'حدث خطأ أثناء إضافة العقد: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($staffID, string $id, Request $request)
    {
        $staff = Staff::findOrFail($staffID);

        return view('staff_members.employee_salary_adjustments.show', [
            'employeeSalaryAdjustment' => EmployeeSalaryAdjustments::findOrFail($id),
            'staff' => $staff,
            'from' => $request->query('from') ? $request->query('from') : null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($staffID, string $id, Request $request)
    {
        $staff = Staff::findOrFail($staffID);
        $employeeSalaryAdjustment = EmployeeSalaryAdjustments::findOrFail($id);

        return view('staff_members.employee_salary_adjustments.edit', [
            'from' => $request->query('from') ? $request->query('from') : null,
            'staff' => $staff,
            'employeeSalaryAdjustment' => $employeeSalaryAdjustment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($staffID, Request $request, string $id)
    {
        // dd($staffID, $id);
        try {
           $validated = $request->validate([
                "type" => 'required|in:allowance,deduction',
                'amount_type' => 'required|in:fixed,percentage',
                'amount' => 'required|numeric|min:0',
                "reason" => 'nullable|string|max:1000',
            ]);

            $employeeSalaryAdjustment = EmployeeSalaryAdjustments::findOrFail($id);

            $employeeSalaryAdjustment->update([
                'amount' => $validated['amount'],
                'amount_type' => $validated['amount_type'],
                'reason' => $validated['reason'] ?? null,
                'type' => $validated['type'],
            ]);

            return redirect()->route('employee_salary_adjustments.index', ['staffID' => $staffID, 'from' => $request->query('from')])->with('success', 'تم تعديل التعديل بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('employee_salary_adjustments.index', ['staffID' => $staffID, 'from' => $request->query('from')])->with('error', 'حدث خطأ أثناء تعديل التعديل: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($staffID, string $id)
    {
        try {
            $empSalAdj = EmployeeSalaryAdjustments::findOrFail($id);

            $empSalAdj->delete();

            return redirect(url()->previous())->with('success', 'تم حذف التعديل بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء حذف التعديل: ' . $e->getMessage());
        }
    }

    public function toggle($id)
    {
        try {
            $empSalAdj = EmployeeSalaryAdjustments::findOrFail($id);
            // dd($empSalAdj);

            $empSalAdj->update(['is_applied' => !$empSalAdj->is_applied]);

            return redirect(url()->previous())->with('success', 'تم تغيير حالة التعديل بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تغيير حالة التعديل: ' . $e->getMessage());
        }
    }
}
