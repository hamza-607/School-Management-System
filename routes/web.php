<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeSalaryAdjustmentsController;
use App\Http\Controllers\FinishedSessionController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\passFailListController;
use App\Http\Controllers\ScoreContoller;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\ScoreCrontroller;
use App\Http\Controllers\SectionScoresController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SectionSubjectTeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPenaltiesController;
use App\Http\Controllers\SubjectController;
use App\Models\FinishedSession;
use App\Models\Student;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// note:

Route::get('LogInPage', [AuthController::class, 'LogInPage'])->name('login');
Route::post('logIn', [AuthController::class, 'logIn'])->name('storeLogIn');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('emailVarification', [AuthController::class, 'emailVarification'])->name('emailVarification');
Route::post('VerificationCode', [AuthController::class, 'sendVerificationCode'])->name('sendVerificationCode');
Route::get('VerificationCode/{userID}', [AuthController::class, 'VerificationCode'])->name('VerificationCode');
Route::post('checkVerificationCode/{userID}', [AuthController::class, 'checkVerificationCode'])->name('checkVerificationCode');
Route::get('forgotPassword/{userID}', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('forgotPasswordStore/{userID}', [AuthController::class, 'forgotPasswordStore'])->name('forgotPasswordStore');

Route::get('account/staff/{staffID}', [StaffController::class, 'makeAnAccount'])->name('account.create')->middleware('signed');
Route::post('account/staff/{staffID}', [StaffController::class, 'accountStore'])->name('account.store')->middleware('signed');

Route::middleware(['auth', 'currentYearAndSemester'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->withoutMiddleware('currentYearAndSemester')->name('dashboard');

    Route::get('academicYears', [AcademicYearController::class, 'index'])->withoutMiddleware('currentYearAndSemester')->name('academicYears.index');
    Route::post('academicYears', [AcademicYearController::class, 'store'])->withoutMiddleware('currentYearAndSemester')->name('academicYears.store');
    Route::post('academicYears/semesters', [AcademicYearController::class, 'storeSemester'])->withoutMiddleware('currentYearAndSemester')->name('semesters.store');
    Route::put('academicYears/{year}/setCurrent', [AcademicYearController::class, 'setYearCurrent'])->withoutMiddleware('currentYearAndSemester')->name('academicYears.setCurrent');
    Route::put('semesters/{semester}/setCurrent', [AcademicYearController::class, 'setSemesterCurrent'])->withoutMiddleware('currentYearAndSemester')->name('semesters.setCurrent');

    Route::get('editPasswordPage', [AuthController::class, 'editPassword'])->withoutMiddleware('currentYearAndSemester')->name('editPasswordPage');
    Route::post('editPassword', [AuthController::class, 'editPasswordStore'])->withoutMiddleware('currentYearAndSemester')->name('editPasswordStore');

    Route::get('students/pdf', [StudentController::class, 'pdfStudentsPage'])->name('students.pdf')->withoutMiddleware('currentYearAndSemester');
    Route::resource('students', StudentController::class);
    Route::get('toggleStatus/{studentID}', [StudentController::class, 'toggle'])->name('toggleStatus');
    Route::prefix('student/{studentID}')
        ->group(function () {
            Route::get('academicYear/pdf', [StudentController::class, 'pdfacademicYearPage'])->name('students.academicYear.pdf')->withoutMiddleware('currentYearAndSemester');

            Route::get('addFile', [StudentController::class, 'addFile'])->name('student.addFile');
            Route::post('files', [StudentController::class, 'saveFile'])->name('student.saveFile');

            Route::resource('penalties', StudentPenaltiesController::class);

            Route::get('scores/pdf', [ScoreController::class, 'pdfScoresPage'])->name('students.scores.pdf')->withoutMiddleware('currentYearAndSemester');
            Route::get('scores', [ScoreController::class, 'index'])->name('scores.index');
            Route::put('scores/update', [ScoreController::class, 'updateScores'])->name('scores.update');

            Route::get('attendanceRecords/pdf', [AttendanceRecordController::class, 'pdfAttendanceStudentPage'])->name('students.attendanceRecords.pdf')->withoutMiddleware('currentYearAndSemester');
            Route::get('attendanceRecords', [AttendanceRecordController::class, 'studentIndex'])->name('student.attendanceRecords.index');
        });
    Route::get('penalties/{penaltyID}', [StudentPenaltiesController::class, 'penaltyStatusToggel'])->name('penaltyStatus');

    Route::resource('subjects', SubjectController::class);
    Route::get('subjectToggleStatus/{subjectID}', [SubjectController::class, 'toggle'])->name('subjectToggleStatus');
    Route::get('subjects/{subject}/addFile', [SubjectController::class, 'addFile'])->name('subject.addFile');
    Route::post('subjects/{subject}/files', [SubjectController::class, 'saveFile'])->name('subject.saveFile');

    Route::resource('guardians', GuardianController::class);
    Route::get('guardians/{guardian}/addFile', [GuardianController::class, 'addFile'])->name('guardian.addFile');
    Route::post('guardians/{guardian}/files', [GuardianController::class, 'saveFile'])->name('guardian.saveFile');

    Route::resource('staff_members', StaffController::class);
    Route::get('staffToggleStatus/{staffID}', [StaffController::class, 'toggle'])->name('staffToggleStatus');
    Route::get('UserToggleStatus/{staffID}', [StaffController::class, 'userToggle'])->name('UserToggleStatus');
    Route::get('staff_member/{staff}/addFile', [StaffController::class, 'addFile'])->name('staff_members.addFile');
    Route::post('staff_members/{staff}/files', [StaffController::class, 'saveFile'])->name('staff_members.saveFile');

    Route::resource('staff_member/{staffID}/employee_salary_adjustments', EmployeeSalaryAdjustmentsController::class);
    Route::get('employee_salary_adjustmentsToggleStatus/{contractID}', [EmployeeSalaryAdjustmentsController::class, 'toggle'])->name('employee_salary_adjustmentsToggleStatus');

    Route::resource('grades', GradeController::class);

    Route::resource('sections', SectionController::class);
    Route::prefix('section/{sectionID}')
        ->group(function () {
            Route::resource('studySchedules', SectionSubjectTeacherController::class);
            Route::get('session/{sessionID}/control', [SectionSubjectTeacherController::class, 'controlSession'])->name('controlSession');
            Route::post('session/{sessionID}/attendance', [FinishedSessionController::class, 'finish'])->name('finishedSession');

            Route::get('addFile', [SectionController::class, 'addFile'])->name('sections.addFile');
            Route::post('files', [SectionController::class, 'saveFile'])->name('sections.saveFile');

            Route::get('scores/pdf', [SectionScoresController::class, 'pdfSectionScoresPage'])->name('section.scores.pdf')->withoutMiddleware('currentYearAndSemester');
            Route::get('scores/create', [SectionScoresController::class, 'create'])->name('sectionScores.create');
            Route::post('scores', [SectionScoresController::class, 'store'])->name('sectionScores.store');

            Route::get('passFailLists/pdf', [passFailListController::class, 'pdfPassFailPage'])->name('section.passFailLists.pdf')->withoutMiddleware('currentYearAndSemester');
            Route::get('passFailLists/create', [passFailListController::class, 'create'])->name('passFailLists.create')->middleware('nextYearMiddleware');
            Route::post('passFailLists', [passFailListController::class, 'store'])->name('passFailLists.store')->middleware('nextYearMiddleware');

            Route::get('attendanceRecords/pdf', [AttendanceRecordController::class, 'pdfAttendanceSectionPage'])->name('section.attendanceRecords.pdf')->withoutMiddleware('currentYearAndSemester');
            Route::get('attendanceRecords', [AttendanceRecordController::class, 'sectionIndex'])->name('sections.attendanceRecords.index');
        });
    Route::get('section/scores', [SectionScoresController::class, 'index'])->name('sectionScores.index');
    Route::get('passFailLists', [passFailListController::class, 'index'])->name('passFailLists.index');

    Route::post('quizzes', [ScoreController::class, 'storeQuiz'])->name('quizzes.store');

    Route::get('studySchedules', [SectionSubjectTeacherController::class, 'superIndex'])->name('studySchedules.superIndex');
    Route::get('session/{sessionID}', [SectionSubjectTeacherController::class, 'sessionStatusToggel'])->name('sessionStatus');
    Route::get('activeSessions', [SectionSubjectTeacherController::class, 'activeSessions'])->name('activeSessions');

    Route::get('finishedSessions', [FinishedSessionController::class, 'index'])->name('finishedSessions.index');
    Route::get('finishedSessions/{sessionID}', [FinishedSessionController::class, 'show'])->name('finishedSessions.show');
    Route::delete('finishedSessions/{sessionID}', [FinishedSessionController::class, 'destroy'])->name('finishedSessions.destroy');

    Route::get('setYearCurrent/{yearID}', [AcademicYearController::class, 'setSessionYear'])->name('setYearCurrent');
    Route::get('Services Guide', function () {
        return view('servicesGuide');
    })->name('ServicesGuide')->withoutMiddleware('currentYearAndSemester');


});

