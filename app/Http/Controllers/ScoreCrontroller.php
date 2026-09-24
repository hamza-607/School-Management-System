<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class ScoreCrontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($studentID)
    {
        $theStudent = Student::with(['scores', 'section.section_subject_teachers.subject'])->findOrFail($studentID);
        $subjects = Subject::whereIn(
            'id',
            $theStudent->section->section_subject_teachers
                ->unique('subject_id')
                ->pluck('subject_id')
                ->toArray()
        )->with(['scores' => function ($q) use ($studentID, $theStudent) {
            $q->where('student_id', $studentID);
        }])
            ->get();

        dd($subjects);

        return view('students.scores.index', [
            'theStudent' => $theStudent,
        ]);
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
