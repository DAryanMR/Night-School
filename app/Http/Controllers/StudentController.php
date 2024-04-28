<?php

namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Course;
use App\Models\Enrolment;
use App\Models\ClassSchedule;
use App\Models\ExamTimetable;
use App\Models\Assignment;
use App\Models\StudentAssignment;
use App\Models\Teacher;


class StudentController extends Controller
{
    public function studentHome()
    {
        $student = Session::get('s_id');
        $courses = Course::all();
        $studentCourses = Enrolment::where('student_id', $student)->get();
        $courseIds = $studentCourses->pluck('course_id')->toArray();
        $studentCourses = Course::whereIn('id', $courseIds)->get();

        return view('student.home.studentHome', compact('studentCourses'));
    }

    public function enroll()
    {
        $student = Session::get('s_id');
        $courses = Course::all();
        $studentCourses = Enrolment::where('student_id', $student)->get();
        $courseIds = $studentCourses->pluck('course_id')->toArray();
        $studentCourses = Course::whereIn('id', $courseIds)->get();
        return view('student.home.enroll', compact('courses', 'studentCourses'));
    }

    public function storeEnrolment(Request $request)
    {

        $student = Session::get('s_id');

        // Validate the form data
        $validatedData = $request->validate([
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
        ]);

        // Process the selected courses
        $selectedCourses = $validatedData['courses'];

        // Perform any additional logic with the selected courses, such as saving to the database or performing actions on each course
        foreach ($selectedCourses as $course) {
            $enrollment = new Enrolment();
            $enrollment->student_id = $student;
            $enrollment->course_id = $course;
            $enrollment->save();
        }

        // Redirect or show a success message
        return redirect('student/enroll');
    }

    public function deleteCourse($id)
    {
        $student_id = Session::get('s_id');
        if (Enrolment::where('student_id', '=', $student_id)
            ->where('course_id', '=', $id)->first()->delete()
        ) {
            return redirect('student/enroll');
        }
    }

    public function studentClassSchedule()
    {
        $studentId = Session::get('s_id');
        $enrollments = Enrolment::where('student_id', $studentId)->get();
        $schedules = ClassSchedule::whereIn('course_id', $enrollments->pluck('course_id'))->get();
        $routine = [];

        foreach ($schedules as $schedule) {
            $dayOfWeek = $schedule->day_of_week;
            $course = Course::find($schedule->course_id);

            if ($course) {
                $courseName = $course->title;
                $startTime = $schedule->start_time;
                $endTime = $schedule->end_time;

                if (!isset($routine[$dayOfWeek])) {
                    $routine[$dayOfWeek] = [];
                }

                $overlap = false;

                if (isset($routine[$dayOfWeek])) {
                    foreach ($routine[$dayOfWeek] as $existingCourseKey => $existingCourse) {
                        $existingStartTime = $existingCourse['startTime'];
                        $existingEndTime = $existingCourse['endTime'];

                        if (
                            $startTime >= $existingStartTime && $startTime < $existingEndTime ||
                            $endTime > $existingStartTime && $endTime <= $existingEndTime
                        ) {
                            $overlap = true;
                            $routine[$dayOfWeek][$existingCourseKey]['overlap'] = true; // Set overlap to true for existing course
                        }
                    }
                }

                $routine[$dayOfWeek][] = [
                    'dayOfWeek' => $dayOfWeek,
                    'courseName' => $courseName,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'overlap' => $overlap,
                ];
            }
        }

        // Sort the courses within each day based on start time
        foreach ($routine as $dayOfWeek => $courses) {
            usort($courses, function ($a, $b) {
                return strtotime($a['startTime']) - strtotime($b['startTime']);
            });
            $routine[$dayOfWeek] = $courses;
        }

        return view('student.home.studentRoutine', compact('routine'));
    }

    public function studentExamSchedule()
    {
        $studentId = Session::get('s_id');
        $enrollments = Enrolment::where('student_id', $studentId)->get();
        $schedules = ExamTimetable::whereIn('course_id', $enrollments->pluck('course_id'))->get();
        $routine = [];

        foreach ($schedules as $schedule) {
            $course = Course::find($schedule->course_id);

            if ($course) {
                $courseID = $course->id;
                $courseName = $course->title;
                $examType = $schedule->exam_type;
                $date = $schedule->date;
                $startTime = $schedule->start_time;
                $endTime = $schedule->end_time;
                $overlap = false;

                foreach ($routine as &$existingExam) {
                    if (
                        $existingExam['date'] === $date &&
                        (
                            ($startTime >= $existingExam['start_time'] && $startTime < $existingExam['end_time']) ||
                            ($endTime > $existingExam['start_time'] && $endTime <= $existingExam['end_time'])
                        )
                    ) {
                        $overlap = true;
                        $existingExam['overlap'] = true; // Set overlap to true for existing exam
                        break; // Exit the loop once overlap is found
                    }
                }

                $routine[] = [
                    "course_id" => $courseID,
                    'course_name' => $courseName,
                    "exam_type" => $examType,
                    "date" => $date,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "overlap" => $overlap,
                ];
            }
        }

        return view('student.home.studentExamSchedule', compact('routine'));
    }

    public function studentAssignments()
    {
        $studentId = Session::get('s_id');
        $enrollments = Enrolment::where('student_id', $studentId)->get();
        $assignments = Assignment::whereIn('course_id', $enrollments->pluck('course_id'))->get();

        $student_assignments = [];

        foreach ($assignments as $assignment) {
            $teacher = Teacher::find($assignment->teacher_id);
            $course = Course::find($assignment->course_id);
            if ($teacher && $course) {
                $student_assignments[] = [
                    "id" => $assignment->id,
                    "teacher_name" => $teacher->name,
                    "course_name" => $course->title,
                    "submission" => $assignment->submission_date,
                ];
            }
        }
        return view("student.home.assignments", compact("student_assignments"));
    }

    public function downloadAssignment($assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $filePath = 'assignments/' . $assignment->question_file;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return redirect()->back()->with('error', 'File not found!');
        }
    }

    public function uploadAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        $course_name = Course::find($assignment->course_id)->title;

        return view('student.home.uploadAssignment', compact('assignment', 'course_name'));
    }

    public function storeAssignment(Request $request, $id)
    {
        $studentID = Session::get('s_id');

        // Get the file from the request
        $file = $request->file('file');

        // Generate a unique file name
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Move the uploaded file to the desired storage location
        $file->storeAs('assignments', $fileName);

        // Create a new assignment record
        $student_assignment = new StudentAssignment();
        $student_assignment->student_id = $studentID;
        $student_assignment->assignment_id = $id;
        $student_assignment->answer_file = $fileName;
        if ($student_assignment->save()) {
            return redirect('student/assignment-details/' . $id);
        }
    }

    public function assignmentDetails($id)
    {
        $student_id = Session::get('s_id');
        $assignment = Assignment::findOrFail($id);

        $course_name = Course::find($assignment->course_id)->title;
        $teacher_name = Teacher::find($assignment->teacher_id)->name;
        $student_assignment = StudentAssignment::where('student_id', $student_id)
            ->where('assignment_id', $id)->first();

        return view('student.home.assignmentDetails', compact('course_name', 'teacher_name', 'student_assignment'));
    }

    public function downloadAssignmentSolution($assignmentId)
    {
        $assignment = StudentAssignment::findOrFail($assignmentId);
        $filePath = 'assignments/' . $assignment->answer_file;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return redirect()->back()->with('error', 'File not found!');
        }
    }
}
