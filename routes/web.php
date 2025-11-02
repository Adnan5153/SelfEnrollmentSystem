<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\ClassController;
use App\Http\Controllers\Admin\Auth\GradeController;
use App\Http\Controllers\Teacher\Auth\ExamController;
use App\Http\Controllers\Teacher\Auth\MarkController;
use App\Http\Controllers\Teacher\Auth\NoticeController;
use App\Http\Controllers\Teacher\Auth\MessageController;
use App\Http\Controllers\Teacher\Auth\RemarksController;
use App\Http\Controllers\Teacher\Auth\RoutineController;
use App\Http\Controllers\Admin\Auth\AddStudentController;
use App\Http\Controllers\Admin\Auth\AddSubjectController;
use App\Http\Controllers\Admin\Auth\AddTeacherController;
use App\Http\Controllers\Admin\Auth\CreditPerYearController;
use App\Http\Controllers\Admin\Auth\AllStudentController;
use App\Http\Controllers\Admin\Auth\AllTeacherController;
use App\Http\Controllers\Admin\Auth\DepartmentController;
use App\Http\Controllers\Teacher\Auth\ProgressController;
use App\Http\Controllers\Teacher\Auth\ResourceController;
use App\Http\Controllers\Teacher\Auth\ClassListController;
use App\Http\Controllers\Teacher\Auth\GradebookController;
use App\Http\Controllers\Admin\Auth\ClassRoutineController;
use App\Http\Controllers\Admin\Auth\PrerequisiteController;
use App\Http\Controllers\Teacher\Auth\AssignmentController;
use App\Http\Controllers\Teacher\Auth\AttendanceController;
use App\Http\Controllers\Student\Auth\StudentExamController;
use App\Http\Controllers\Teacher\Auth\StudentListController;
use App\Http\Controllers\Admin\Auth\CourseOfferingController;
use App\Http\Controllers\Admin\Auth\CourseOverviewController;
use App\Http\Controllers\Student\Auth\FullCalenderController;
use App\Http\Controllers\Student\Auth\StudentMarksController;
use App\Http\Controllers\Teacher\Auth\AnnouncementController;
use App\Http\Controllers\Admin\Auth\AddExamScheduleController;
use App\Http\Controllers\Student\Auth\StudentNoticeController;
use App\Http\Controllers\Student\Auth\OfferedCoursesController;
use App\Http\Controllers\Student\Auth\StudentLibraryController;
use App\Http\Controllers\Student\Auth\StudentCourseOverviewController;
use App\Http\Controllers\Student\Auth\StudentProfileController;
use App\Http\Controllers\Student\Auth\StudentSubjectController;
use App\Http\Controllers\Admin\Auth\CourseDistributionController;
use App\Http\Controllers\Admin\Auth\CreditDistributionController;
use App\Http\Controllers\Student\Auth\StudentDashboardController;
use App\Http\Controllers\Student\Auth\StudentClassRoutineController;
use App\Http\Controllers\Student\GpaController; // Import GpaController


Route::get('/', function () {
    return view('welcome');
});
Route::get('/userlogin', function () {
    return view('userlogin'); // Replace 'userlogin' with the actual view name if different
})->name('userlogin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // Student Routes
    Route::get('/addstudent', [AddStudentController::class, 'create'])->name('register.student.and.parent');
    Route::post('/addstudent', [AddStudentController::class, 'store'])->name('register.student.and.parent.store');

    Route::get('/allstudent', [AllStudentController::class, 'index'])->name('allstudent.index');
    Route::put('/allstudent/{student_id}', [AllStudentController::class, 'update'])->name('allstudent.update');
    Route::delete('/allstudent/{student_id}', [AllStudentController::class, 'destroy'])->name('allstudent.destroy');

    // Parent Routes
    Route::get('/allparents', [AllStudentController::class, 'showParents'])->name('allparents.index');
    Route::put('/allparents/{parent_id}', [AllStudentController::class, 'updateParent'])->name('allparents.update');
    Route::delete('/allparents/{parent_id}', [AllStudentController::class, 'destroyParent'])->name('allparents.destroy');

    // Teacher Routes
    Route::get('/addteacher', [AddTeacherController::class, 'create'])->name('addteacher.create');
    Route::post('/addteacher', [AddTeacherController::class, 'store'])->name('addteacher.store');
    Route::get('/allteachers', [AllTeacherController::class, 'index'])->name('allteachers.index');
    Route::put('/allteachers/{id}', [AllTeacherController::class, 'update'])->name('allteachers.update');
    Route::delete('/allteachers/{id}', [AllTeacherController::class, 'destroy'])->name('allteachers.destroy');

    // Subject Routes
    Route::get('/subjects', [AddSubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [AddSubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [AddSubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit', [AddSubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [AddSubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [AddSubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::get('/subjects/filter', [AddSubjectController::class, 'filterSubjects'])->name('subjects.filter');

    // Prerequisite Routes
    Route::get('/prerequisite', [PrerequisiteController::class, 'index'])->name('prerequisite.index');
    Route::post('/prerequisite', [PrerequisiteController::class, 'store'])->name('prerequisite.store');
    Route::put('/prerequisite/{id}', [PrerequisiteController::class, 'update'])->name('prerequisite.update');
    Route::delete('/prerequisite/{id}', [PrerequisiteController::class, 'destroy'])->name('prerequisite.destroy');

    // Course Overview Routes
    Route::get('/courseoverview', [CourseOverviewController::class, 'index'])->name('courseoverview.index');
    Route::post('/courseoverview', [CourseOverviewController::class, 'store'])->name('courseoverview.store');
    Route::get('/courseoverview/{id}/edit', [CourseOverviewController::class, 'edit'])->name('courseoverview.edit');
    Route::put('/courseoverview/{id}', [CourseOverviewController::class, 'update'])->name('courseoverview.update');
    Route::delete('/courseoverview/{id}', [CourseOverviewController::class, 'destroy'])->name('courseoverview.destroy');
    Route::delete('/courseoverview/{id}/drop', [CourseOverviewController::class, 'drop'])->name('courseoverview.drop');

    // Course Offering Routes
    Route::get('/courseoffering', [CourseOfferingController::class, 'index'])->name('courseoffering.index');
    Route::post('/courseoffering', [CourseOfferingController::class, 'store'])->name('courseoffering.store');
    Route::post('/courseoffering/offer-to-class', [CourseOfferingController::class, 'offerToClass'])->name('courseoffering.offerToClass');
    Route::get('/courseoffering/{id}/edit', [CourseOfferingController::class, 'edit'])->name('courseoffering.edit');
    Route::put('/courseoffering/{id}', [CourseOfferingController::class, 'update'])->name('courseoffering.update');
    Route::get('/courseoffering/{id}/subjects', [CourseOfferingController::class, 'getSubjects'])->name('courseoffering.subjects');
    Route::get('/courseoffering/selected', [CourseOfferingController::class, 'selected'])->name('courseoffering.selected');
    Route::put('/courseoffering/selected/{id}', [CourseOfferingController::class, 'updateOffering'])->name('courseoffering.selected.update');
    Route::delete('/courseoffering/selected/{id}', [CourseOfferingController::class, 'deleteOffering'])->name('courseoffering.selected.delete');
    Route::delete('/courseoffering/selected', [CourseOfferingController::class, 'clearAll'])->name('courseoffering.selected.clear');

    Route::delete('/courseoffering/{id}', [CourseOfferingController::class, 'destroy'])->name('courseoffering.destroy');

    // Credit Ditribution Routes
    Route::get('/creditdistribution', [CreditDistributionController::class, 'index'])->name('creditdistribution.index');
    Route::post('/creditdistribution', [CreditDistributionController::class, 'store'])->name('creditdistribution.store');
    Route::get('/creditdistribution/{id}/edit', [CreditDistributionController::class, 'edit'])->name('creditdistribution.edit');
    Route::put('/creditdistribution/{id}', [CreditDistributionController::class, 'update'])->name('creditdistribution.update');
    Route::delete('/creditdistribution/{id}', [CreditDistributionController::class, 'destroy'])->name('creditdistribution.destroy');
    Route::post('/creditdistribution/filter-subjects', [CreditDistributionController::class, 'filterSubjects'])->name('creditdistribution.filterSubjects');

    // Course Distribution Routes
    Route::get('/coursedistribution', [CourseDistributionController::class, 'index'])->name('coursedistribution.index');
    Route::post('/coursedistribution', [CourseDistributionController::class, 'store'])->name('coursedistribution.store');
    Route::get('/coursedistribution/{id}/edit', [CourseDistributionController::class, 'edit'])->name('coursedistribution.edit');
    Route::put('/coursedistribution/{id}', [CourseDistributionController::class, 'update'])->name('coursedistribution.update');
    Route::delete('/coursedistribution/{id}', [CourseDistributionController::class, 'destroy'])->name('coursedistribution.destroy');

    // Class Routes
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/add', [ClassController::class, 'create'])->name('classes.create');
    Route::post('/classes/store', [ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/edit/{id}', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/update/{id}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/delete/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');

    // Class Routine Routes
    Route::get('/classroutines', [ClassRoutineController::class, 'index'])->name('classroutines.index');
    Route::get('/classroutines/add', [ClassRoutineController::class, 'create'])->name('classroutines.create');
    Route::post('/classroutines/store', [ClassRoutineController::class, 'store'])->name('classroutines.store');
    Route::get('/classroutines/edit/{id}', [ClassRoutineController::class, 'edit'])->name('classroutines.edit');
    Route::put('/classroutines/update/{id}', [ClassRoutineController::class, 'update'])->name('classroutines.update');
    Route::delete('/classroutines/delete/{id}', [ClassRoutineController::class, 'destroy'])->name('classroutines.destroy');
    Route::get('/classroutines/get-subjects', [ClassRoutineController::class, 'getSubjectsByClass'])->name('classroutines.get-subjects');

    // Department Routes
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Admin Routes for Grade Management
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');

    // Exam Schedule Routes
    Route::get('/addexamschedule', [AddExamScheduleController::class, 'index'])->name('examschedule.index');
    Route::post('/addexamschedule', [AddExamScheduleController::class, 'store'])->name('examschedule.store');
    Route::get('/examschedule', [AddExamScheduleController::class, 'show'])->name('examschedule.list');
    Route::get('/examschedule/{id}/edit', [AddExamScheduleController::class, 'edit'])->name('examschedule.edit');
    Route::put('/examschedule/{id}', [AddExamScheduleController::class, 'update'])->name('examschedule.update');
    Route::delete('/examschedule/{id}', [AddExamScheduleController::class, 'destroy'])->name('examschedule.destroy');
    Route::get('/examschedule/get-subjects', [AddExamScheduleController::class, 'getSubjectsByClass'])->name('examschedule.get-subjects');

    // Credit Requirement (per year)
    Route::get('/credit-per-year', [CreditPerYearController::class, 'index'])->name('creditperyear.index');
    Route::get('/credit-per-year/create', [CreditPerYearController::class, 'create'])->name('creditperyear.create');
    Route::post('/credit-per-year', [CreditPerYearController::class, 'store'])->name('creditperyear.store');
    Route::put('/credit-per-year/{id}', [CreditPerYearController::class, 'update'])->name('creditperyear.update');
    Route::delete('/credit-per-year/{id}', [CreditPerYearController::class, 'destroy'])->name('creditperyear.destroy');
});

// Teacher Routes
Route::prefix('teacher')->middleware('auth:teacher')->group(function () {
    Route::get('/addmarks', [MarkController::class, 'create'])->name('teacher.addmarks');
    Route::post('/addmarks', [MarkController::class, 'store'])->name('teacher.storemarks');
    Route::post('/fetch-students', [MarkController::class, 'fetchStudents'])->name('teacher.fetchstudents');
    Route::post('/fetch-subjects', [MarkController::class, 'fetchSubjects'])->name('teacher.fetchsubjects');
    Route::post('/marks/fetch-students-by-subject', [MarkController::class, 'fetchStudentsBySubject'])->name('teacher.fetchstudents.bysubject');

    Route::get('/classes', [ClassListController::class, 'index'])->name('teacher.classes');
    Route::get('/students', [StudentListController::class, 'index'])->name('teacher.students');

    Route::get('/attendance/take', [AttendanceController::class, 'take'])->name('teacher.attendance.take');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('teacher.attendance.report');

    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('teacher.assignments.create');
    Route::get('/assignments/manage', [AssignmentController::class, 'manage'])->name('teacher.assignments.manage');
    Route::get('/assignments/submissions', [AssignmentController::class, 'submissions'])->name('teacher.assignments.submissions');

    Route::get('/exam/schedule', [ExamController::class, 'schedule'])->name('teacher.exam.schedule');
    Route::get('/exam/gradebook', [GradebookController::class, 'index'])->name('teacher.gradebook');

    Route::get('/routine', [RoutineController::class, 'index'])->name('teacher.routine');

    Route::get('/progress', [ProgressController::class, 'index'])->name('teacher.progress');
    Route::put('/progress/{id}', [ProgressController::class, 'update'])->name('progress.update');
    Route::delete('/progress/{id}', [ProgressController::class, 'destroy'])->name('progress.destroy');

    Route::get('/remarks', [RemarksController::class, 'index'])->name('teacher.remarks');

    Route::get('/messages', [MessageController::class, 'index'])->name('teacher.messages');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('teacher.announcements');

    Route::get('/notice', [NoticeController::class, 'index'])->name('teacher.notice');

    Route::get('/resources/upload', [ResourceController::class, 'upload'])->name('teacher.resources.upload');
    Route::get('/resources/shared', [ResourceController::class, 'shared'])->name('teacher.resources.shared');
});

// Student Routes
Route::prefix('student')->middleware('auth:student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/studentprofile', [StudentProfileController::class, 'index'])->name('student.profile.index');
    Route::put('/studentprofile/parent', [StudentProfileController::class, 'updateParent'])->name('student.profile.update.parent');
    Route::put('/studentprofile/contact', [StudentProfileController::class, 'updateContact'])->name('student.profile.update.contact');

    Route::get('/marks', [StudentMarksController::class, 'index'])->name('student.marks');

    Route::get('/subjects', [StudentSubjectController::class, 'index'])->name('student.subjects');
    Route::post('/enroll-courses', [StudentSubjectController::class, 'enrollBulk'])->name('student.enroll.courses.bulk');
    Route::delete('/drop-course/{subject}', [StudentSubjectController::class, 'drop'])->name('student.drop.course');

    Route::get('/courseoverview', [StudentCourseOverviewController::class, 'index'])->name('studentcourseoverview.index');
    Route::post('/enroll-course/{subject}', [StudentCourseOverviewController::class, 'enrollCourse'])->name('student.enroll.course');

    Route::get('/library', [StudentLibraryController::class, 'index'])->name('student.library');
    Route::get('/notice', [StudentNoticeController::class, 'index'])->name('student.notice');
    Route::get('/exam-schedule', [StudentExamController::class, 'index'])->name('student.exam.schedule');
    // GPA Management Routes
    Route::get('/gpa', [GpaController::class, 'index'])->name('gpa'); // View GPA page
    Route::post('/gpa', [GpaController::class, 'store'])->name('gpa.store'); // Store GPA entry

    // Student Class Routine Routes
    Route::get('/classroutine', [StudentClassRoutineController::class, 'index'])
        ->name('student.classroutine');
    Route::get('/fullcalender', [FullCalenderController::class, 'index']);

    Route::post('/fullcalenderAjax', [FullCalenderController::class, 'ajax']);
    Route::get('/offered-courses', [OfferedCoursesController::class, 'index'])->name('student.offered.courses');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin-auth.php';
require __DIR__ . '/teacher-auth.php';
require __DIR__ . '/student-auth.php';
