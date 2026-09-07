<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Student_penalties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function store(AttendanceRequest $request, $sectionID, $sessionID)
    {
        // dd($request->all());
        // "attendance" => array:1 [▼
        //     0 => array:2 [▼
        //       "student_id" => "2"
        //       "status" => "absent"
        //     ]
        //   ]
        //   "penalties" => array:2 [▼
        //     0 => array:4 [▼
        //       "student_id" => "2"
        //       "" => "انذار"
        //       "reason" => "غياب حصة درسية"
        //       "notes" => null
        //     ]
        //     1 => array:4 [▼
        //       "student_id" => "2"
        //       "penalty_type" => "فصل"
        //       "reason" => "غياب 5 حصص درسية متتالية من دون عذر"
        //       "notes" => "مدة الفصل شهرين"
        //     ]
        //   ]
        try {
            $validated = $request->validated();
            // dd($validated);

            if ($request->has('attendance') && $validated['attendance'] !== null) {
                foreach ($validated['attendance'] as $attendance) {
                    Attendance::create([
                        'student_id' => $attendance['student_id'],
                        'section_subject_teacher_id' => $sessionID,
                        'section_id' => $sectionID,
                        'status' => $attendance['status'],
                    ]);
                }
            }

            if ($request->has('penalties') && $validated['penalties'] !== null) {
                foreach ($validated['penalties'] as $penalty) {
                    Student_penalties::create([
                        'penalty_type' => $penalty['penalty_type'],
                        'reason' => $penalty['reason'],
                        'user_id' => Auth::id(),
                        'updated_by' => null,
                        'status' => 'pending',
                        'notes' => $penalty['notes'] ?? null,
                        'student_id' => $penalty['student_id'],
                    ]);
                }
            }

            return redirect(url()->previous())->with('success', 'تم حفظ سجل الحضور بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ اثناء حفظ سجل الحضور' . $e->getMessage());
        }
    }
}
