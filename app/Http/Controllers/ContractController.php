<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Staff;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($staffID, Request $request)
    {
        $staff = Staff::findOrFail($staffID);

        $contracts = $staff->contracts;
        // dd($contracts);

        return view('staff_members.employee_salary_adjustments.index', [
            'contracts' => $contracts,
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
        try {
            $validated = $request->validate([
                'contract_type' => 'required|in:fixed,percentage',
                'salary' => 'required|numeric|min:0',
            ]);

            Contract::create([
                'staff_id' => $staffID,
                'salary' => $validated['salary'],
                'contract_type' => $validated['contract_type'],
                'is_active' => true,
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
            'theContract' => Contract::findOrFail($id),
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
        $theContract = Contract::findOrFail($id);

        return view('staff_members.employee_salary_adjustments.edit', [
            'from' => $request->query('from') ? $request->query('from') : null,
            'staff' => $staff,
            'theContract' => $theContract,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($staffID, Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'contract_type' => 'required|in:fixed,percentage',
                'salary' => 'required|numeric|min:0',
            ]);

            $theContract = Contract::findOrFail($id);

            $theContract->update([
                'salary' => $validated['salary'],
                'contract_type' => $validated['contract_type'],
                'is_active' => true,
            ]);

            return redirect()->route('employee_salary_adjustments.index', ['staffID' => $staffID, 'from' => $request->query('from')])->with('success', 'تم إضافة العقد بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('employee_salary_adjustments.index', ['staffID' => $staffID, 'from' => $request->query('from')])->with('error', 'حدث خطأ أثناء إضافة العقد: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($staffID, string $id)
    {
        try {
            $contract = Contract::findOrFail($id);

            $contract->delete();

            return redirect(url()->previous())->with('success', 'تم حذف العقد بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء حذف العقد: ' . $e->getMessage());
        }
    }

    public function toggle($contractID)
    {
        try {
            $contract = Contract::findOrFail($contractID);

            $contract->update(['is_active' => !$contract->is_active]);

            return redirect(url()->previous())->with('success', 'تم تغيير حالة العقد بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تغيير حالة العفد: ' . $e->getMessage());
        }
    }
}
