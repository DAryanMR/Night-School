<?php

namespace App\Http\Controllers;

use App\Models\CourseAttendance;
use \Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Course;
use App\Models\TeacherCourse;
use App\Models\ClassSchedule;
use App\Models\ExamTimetable;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Enrolment;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use App\Models\Student;

class TeacherController extends Controller
{
    public function teacherHome()
    {
        return view('teacher.home.teacherHome');
    }

    public function addCourseMenu()
    {
        $courses = Course::all();
        return view('teacher.home.selectCourse', compact('courses'));
    }

    public function teacherCourses(Request $request)
    {
        $teacher = Session::get('t_id');
        // Validate the form data
        $validatedData = $request->validate([
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
        ]);

        // Process the selected courses
        $selectedCourses = $validatedData['courses'];
        // Perform any additional logic with the selected courses, such as saving to the database or performing actions on each course
        foreach ($selectedCourses as $course) {
            $teacherCourse = new TeacherCourse();
            $course_exists = TeacherCourse::where('teacher_id', '=', $teacher)
                ->where('course_id', '=', $course)->first();
            if ($course_exists) {
                return redirect('teacher/add-course');
            } else {
                $teacherCourse->teacher_id = $teacher;
                $teacherCourse->course_id = $course;
                $teacherCourse->save();
            }
        }
        // Redirect or show a success message
        return redirect('teacher/manageCourse');
    }

    public function manageCourse()
    {
        $teacher = Session::get('t_id');
        $teacherCourses = TeacherCourse::all();
        $courses = [];
        foreach ($teacherCourses as $teacherCourse) {
            if ($teacher == $teacherCourse->teacher_id) {
                $course = Course::find($teacherCourse->course_id);
                if ($course) {
                    $courses[] = array('id' => $teacherCourse->id, 'c_id' => $teacherCourse->course_id, 'title' => $course->title);
                }
            }
        }

        return view('teacher.home.manageCourses', compact('teacher', 'courses'));
    }

    public function deleteCourse($id)
    {
        $course_id = TeacherCourse::find($id)->course_id;

        if (TeacherCourse::find($id)->delete()) {
            $class_schedules = ClassSchedule::where('course_id', $course_id)->get();
            foreach ($class_schedules as $schedule) {
                ClassSchedule::find($schedule->id)->delete();
            }
            $exam_schedules = ExamTimetable::where('course_id', $course_id)->get();
            foreach ($exam_schedules as $schedule) {
                ExamTimetable::find($schedule->id)->delete();
            }
            return redirect('teacher/manageCourse');
        }
    }

    public function routineMenu()
    {
        $teacher = Session::get('t_id');
        $teacherCourses = TeacherCourse::all();
        $courses = [];
        foreach ($teacherCourses as $teacherCourse) {
            if ($teacher == $teacherCourse->teacher_id) {
                $course = Course::find($teacherCourse->course_id);
                if ($course) {
                    $courses[] = array('c_id' => $teacherCourse->course_id, 'title' => $course->title);
                }
            }
        }
        return view('teacher.home.makeClassRoutine', compact('teacher', 'courses'));
    }

    public function routineEntry($id)
    {
        $course = Course::find($id);
        return view('teacher.home.routineSchedule', compact('course'));
    }

    public function classScheduleStore(Request $request, $id)
    {
        $course = Course::find($id);
        $teacher = Session::get('t_id');
        $validatedData = $request->validate([
            'duration' => 'required|in:1,2',
        ]);

        $duration = $validatedData['duration'];


        // dd(ClassSchedule::all());

        // Loop through the dynamic fields based on the duration
        $schedule = [];
        for ($i = 1; $i <= $duration; $i++) {
            $dayOfWeek = $request->input('day_of_week_' . $i);
            $startTime = $request->input('start_time_' . $i);
            $endTime = $request->input('end_time_' . $i);

            $schedule = new ClassSchedule();
            $schedule->course_id = $course->id;
            $schedule->teacher_id = $teacher;
            $schedule->day_of_week = $dayOfWeek;
            $schedule->start_time = $startTime;
            $schedule->end_time = $endTime;
            $schedule->save();
        }

        return redirect('teacher/manageClassSchedule/' . $id)->with('success', 'Class schedule inserted');
    }

    public function manageClassSchedule($id)
    {
        $course = Course::find($id);
        $teacher = Session::get('t_id');
        $class_schedules = ClassSchedule::where('teacher_id', '=', $teacher)
            ->where('course_id', '=', $course->id)->get();


        $map_schedules = [];
        foreach ($class_schedules as $schedule) {
            $course = Course::find($schedule->course_id);
            $map_schedules[$schedule->course_id] = $course->title;
            $course_title = $course->title;
        }

        return view('teacher.home.manageClassSchedule', compact('class_schedules', 'map_schedules'));
    }

    public function editRoutine($id)
    {
        $course = ClassSchedule::find($id);
        $courseTitle = Course::find($course->course_id)->title;

        return view('teacher.home.updateClassRoutine', compact('course', 'courseTitle'));
    }

    public function updateRoutine(Request $request, $id)
    {
        $day = $request->day;
        $start_time = $request->start_time;
        $end_time = $request->end_time;

        $course = ClassSchedule::find($id);
        $course->day_of_week = $day;
        $course->start_time = $start_time;
        $course->end_time = $end_time;
        if ($course->save()) {
            return redirect('teacher/manageClassSchedule/' . $course->course_id);
        }
    }

    public function deleteRoutine($id)
    {
        $course_id = ClassSchedule::find($id)->course_id;
        if (ClassSchedule::find($id)->delete()) {
            return redirect('teacher/manageClassSchedule/' . $course_id);
        }
    }

    public function examMenu()
    {
        $teacherId = Session::get('t_id');
        $teacherCourses = TeacherCourse::where('teacher_id', $teacherId)->get();
        $courseIds = $teacherCourses->pluck('course_id')->toArray();
        $courses = Course::whereIn('id', $courseIds)->get();
        return view('teacher.home.teacherExamSchedule', compact('courses'));
    }

    public function examScheduleStore(Request $request)
    {
        $examTimetable = new ExamTimetable();
        $examTimetable->course_id = $request->selectedCourse;
        $examTimetable->exam_type = $request->examType;
        $examTimetable->date = $request->date;
        $examTimetable->start_time = $request->startTime;
        $examTimetable->end_time = $request->endTime;

        if ($examTimetable->save()) {
            return redirect('/teacher/home')->with('success', 'Exam schedule added');
        }
    }

    public function assignments()
    {
        $teacherId = Session::get('t_id');
        $teacherCourses = TeacherCourse::where('teacher_id', $teacherId)->get();
        $courseIds = $teacherCourses->pluck('course_id')->toArray();
        $courses = Course::whereIn('id', $courseIds)->get();

        return view('teacher.home.assignments', compact('courses'));
    }



    public function addAssignment($courseId)
    {
        $teacherId = Session::get('t_id');
        $assignmentExists = Assignment::where('teacher_id', $teacherId)
            ->where('course_id', $courseId)->first();
        if (!$assignmentExists) {
            $course = Course::find($courseId);
            return view('teacher.home.addAssignment', compact('course'));
        } else {
            return redirect()->back()->with('error', 'Assignment already exists!');
        }
    }

    public function storeAssignment(Request $request, $id)
    {
        $teacherId = Session::get('t_id');

        $validatedData = $request->validate([
            'file' => 'required|file',
            'submission_date' => 'required|date',
        ]);

        // Get the file from the request
        $file = $request->file('file');

        // Generate a unique file name
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Move the uploaded file to the desired storage location
        $file->storeAs('assignments', $fileName);

        // Create a new assignment record
        $assignment = new Assignment();
        $assignment->teacher_id = $teacherId;
        $assignment->course_id = $id;
        $assignment->question_file = $fileName;
        $assignment->submission_date = $validatedData['submission_date'];
        if ($assignment->save()) {
            return redirect('teacher/assignment-details/' . $id);
        }
    }

    public function deleteAssignment($courseId)
    {
        $teacherId = Session::get('t_id');
        $assignment = Assignment::where('teacher_id', $teacherId)
            ->where('course_id', $courseId)->first();
        if ($assignment) {
            $assignment->delete();
            return redirect('teacher/assignments')->with('success', 'Assignment Deleted');
        } else {
            return redirect('teacher/assignments')->with('error', 'Add Assignment First');
        }
    }

    public function assignmentDetails($courseId)
    {
        $teacherId = Session::get('t_id');
        $teacherAssignments = Assignment::where('teacher_id', $teacherId)
            ->where('course_id', $courseId)->get();

        $assignmentIDs = $teacherAssignments->pluck('id')->toArray();
        $studentAssignments = StudentAssignment::whereIn('assignment_id', $assignmentIDs)->get();

        $assignmentFiltered = [];
        foreach ($studentAssignments as $studentAssignment) {
            $student_Name = Student::find($studentAssignment->student_id)->name;
            $assignmentFiltered[] = [
                'id' => $studentAssignment->id,
                'student_id' => $studentAssignment->student_id,
                'student_name' => $student_Name,
                'remarks' => $studentAssignment->remarks,
                'feedback' => $studentAssignment->feedback,
            ];
        }
        return view('teacher.home.assignmentDetails', compact('assignmentFiltered'));
    }

    public function downloadAssignmentSolution($assignmentId)
    {
        $assignment = StudentAssignment::find($assignmentId);
        $filePath = 'assignments/' . $assignment->answer_file;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return redirect()->back()->with('error', 'File not found!!!!!!');
        }
    }

    public function markAssignment(Request $request, $assignmentId)
    {
        $assignment = StudentAssignment::findOrFail($assignmentId);
        $courseId = Assignment::find($assignment->assignment_id)->course_id;
        $assignment->remarks = $request->remarks;


        if ($assignment->save()) {
            return redirect('teacher/assignment-details/' . $courseId);
        }
    }

    public function giveFeedback(Request $request, $assignmentId)
    {
        $assignment = StudentAssignment::findOrFail($assignmentId);
        $courseId = Assignment::find($assignment->assignment_id)->course_id;
        $assignment->feedback = $request->feedback;


        if ($assignment->save()) {
            return redirect('teacher/assignment-details/' . $courseId);
        }
    }

    public function attendanceMenu()
    {
        $teacher_id = Session::get('t_id');
        $teacher_courses = TeacherCourse::where('teacher_id', $teacher_id)->get();
        $teacher_courses_filtered = [];
        foreach ($teacher_courses as $teacher_course) {
            $course = Course::find($teacher_course->course_id);
            if ($course) {
                $teacher_courses_filtered[] = [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                ];
            }
        }
        return view('teacher.home.attendanceMenu', compact('teacher_courses_filtered'));
    }

    public function addAttendance($courseId)
    {
        return view('teacher.home.addAttendance', compact('courseId'));
    }

    public function storeAttendance(Request $request, $courseId)
    {
        $duration = $request->duration;
        $attendance = new Attendance();
        $attendance->course_id = $courseId;
        $attendance->num_of_classes = $duration;
        $attendance->save();

        for ($i = 1; $i <= $duration; $i++) {
            $courseAttendance = new CourseAttendance();
            $classDate = $request->input('date_' . $i);
            $courseAttendance->course_id = $courseId;
            $courseAttendance->date = $classDate;
            $attendanceExists = CourseAttendance::where('course_id', $courseId)
                ->where('date', $classDate)->first();
            if (!$attendanceExists) {
                $courseAttendance->save();
            }
        }

        if ($attendanceExists) {
            return redirect('teacher/manage-attendance/' . $courseId)->with('error', 'date exists');
        } else {
            return redirect('teacher/manage-attendance/' . $courseId)->with('success', 'attendance added successfully');
        }
    }

    public function manageAttendance($course_id)
    {

        $teacher_id = Session::get('t_id');
        $teacher_course = TeacherCourse::where('teacher_id', $teacher_id)
            ->where('course_id', $course_id)->first();
        $teacher_courses_filtered = [];
        $course = Course::find($teacher_course->course_id);
        if ($course) {
            $course_attendances = CourseAttendance::where('course_id', $course->id)->get();
            foreach ($course_attendances as $course_attendance) {
                $teacher_courses_filtered[] = [
                    'id' => $course_attendance->id,
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'date' => $course_attendance->date,
                ];
            }
        }

        $attendanceSheet = DB::table('student_attendances')
            // student_attendances joined with course_attendances based on attendance_id
            ->join(
                'course_attendances',
                'student_attendances.attendance_id',
                '=',
                'course_attendances.id'
            )
            // joined with students based on student_id 
            ->join(
                'students',
                'student_attendances.student_id',
                '=',
                'students.id'
            )
            // course_attendances further joined with courses using a nested join
            ->join('courses', function ($join) use ($teacher_id) {
                $join->on('course_attendances.course_id', '=', 'courses.id')
                    // This join includes only courses associated with a specific teacher
                    ->join('teacher_courses', function ($join) use ($teacher_id) {
                        $join->on('courses.id', '=', 'teacher_courses.course_id')
                            ->where('teacher_courses.teacher_id', '=', $teacher_id);
                    });
            })
            // Columns date, student_id, student_name, course_name, and attendance are selected
            ->select(
                'course_attendances.date',
                'students.id as student_id',
                'students.name as student_name',
                'courses.title as course_name',
                'student_attendances.attendance'
            )
            ->where('courses.id', $course_id) // Add this line to filter by course ID
            ->orderBy('course_attendances.date', 'asc') // Add this line to sort by date in ascending order
            ->get();


        return view('teacher.home.manageAttendance', compact('teacher_courses_filtered', 'attendanceSheet'));
    }

    public function giveAttendance($attendanceId)
    {
        $courseAttendance = CourseAttendance::find($attendanceId);

        $studentEnrolments = Enrolment::where('course_id', $courseAttendance->course_id)->get();

        $mergedArray = [];
        foreach ($studentEnrolments as $studentEnrolment) {
            $mergedArray[] = [
                'date' => $courseAttendance->date,
                'student_id' => $studentEnrolment->student_id,
                'student_name' => Student::find($studentEnrolment->student_id)->name,
                'attendance_id' => $attendanceId,
            ];
        }

        return view('teacher.home.giveAttendance', compact('mergedArray', 'attendanceId'));
    }

    public function storeGivenAttendance(Request $request, $attendanceId)
    {
        foreach ($request->attendance as $s_id => $info) {
            $studentAttendance = StudentAttendance::where('attendance_id', $attendanceId)
                ->where('student_id', $s_id)->first();
            if (!$studentAttendance) {
                $studentAttendance = new StudentAttendance();
                $studentAttendance->student_id = $s_id;
                $studentAttendance->attendance_id = $attendanceId;

                $studentAttendance->attendance = $info[$attendanceId];
                $studentAttendance->save();
            } else {
                $studentAttendance->attendance = $info[$attendanceId];
                $studentAttendance->save();
            }
        }

        $courseId = CourseAttendance::find($attendanceId)->course_id;

        return redirect('teacher/manage-attendance/' . $courseId);
    }
}
