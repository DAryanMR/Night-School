<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function index()
    {
        if (Session::has("a_name")) {
            return redirect("/admin/home");
        } elseif (Session::has("s_id")) {
            return redirect("/student/home");
        } elseif (Session::has("t_id")) {
            return redirect("/teacher/home");
        } else {
            return view("index");
        }
    }

    public function adminLogin()
    {
        return view("admin.auth.loginAdmin");
    }

    public function adminLogger(Request $request)
    {
        $email = "contact@admin.com";
        $pass = "12345";
        if ($request->email == $email && $request->pass == $pass) {
            // Session::put('is_admin', 1);
            Session::put('a_name', 'Admin');
            Session::put('role', 'admin');
            return redirect('admin/home');
        }
    }

    public function studentLogin()
    {
        return view("student.auth.loginStudent");
    }

    public function studentRegister()
    {
        return view("student.auth.registerStudent");
    }

    public function studentRegistration(Request $request)
    {
        $student = new Student();

        $student_exists = Student::where('email', '=', $request->email)->first();

        if ($student_exists) {
            return redirect()->back()->with('error', 'Email exists');
        } else {
            $student->name = $request->username;
            $student->email = $request->email;
            $student->password = $request->password;

            if ($student->save()) {
                return redirect()->back()->with('success', 'Student registered, login to continue');
            }
        }
    }

    public function studentLogger(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $student = Student::where('email', '=', $email)
            ->where('password', '=', $password)->first();

        if ($student) {
            // Save info in session
            Session::put('s_id', $student->id);
            Session::put('s_name', $student->name);
            Session::put('s_email', $student->email);
            Session::put('role', 'student');
            return redirect('student/home');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function forgotPassStudent()
    {
        return view('student.auth.forgotPass');
    }

    public function resetCheckStudent(Request $request)
    {
        $email = $request->email;
        $name = $request->name;

        $student = Student::where('email', '=', $email)
            ->where('name', '=', $name)->first();

        if ($student) {
            return redirect('student/newPass/' . $student->id);
        } else {
            return redirect()->back()->with('error', 'Email/Name does not match');
        }
    }

    public function newPassStudent($studentId)
    {
        $student = Student::find($studentId);
        $resetCode = rand(100000, 999999);

        return view('student.auth.updatePass', compact('student', 'resetCode'));
    }

    public function updatePassStudent(Request $request, $studentId, $resetCode)
    {
        $student = Student::find($studentId);
        $pass = $request->password;
        $confirmPass = $request->confirmPassword;
        $resetCodeInput = $request->resetCode;

        if ($pass == $confirmPass && $resetCodeInput == $resetCode) {
            $student->password = $pass;
            $student->save();
            return redirect('student/login')->with('success', 'Password updated');
        } else {
            return redirect()->back()->with('error', 'Passwords do not match');
        }
    }

    public function teacherRegister()
    {
        return view("teacher.auth.registerTeacher");
    }

    public function teacherRegistration(Request $request)
    {
        $teacher = new Teacher();

        $teacher_exists = Teacher::where('email', '=', $request->email)->first();

        if ($teacher_exists) {
            return redirect()->back()->with('error', 'Email exists');
        } else {
            $teacher->name = $request->username;
            $teacher->email = $request->email;
            $teacher->password = $request->password;

            if ($teacher->save()) {
                return redirect()->back()->with('success', 'teacher registered, login to continue');
            }
        }
    }

    public function teacherLogin()
    {
        return view('teacher.auth.loginTeacher');
    }

    public function teacherLogger(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $teacher = Teacher::where('email', '=', $email)
            ->where('password', '=', $password)->first();

        if ($teacher) {
            // Save info in session
            Session::put('t_id', $teacher->id);
            Session::put('t_name', $teacher->name);
            Session::put('t_email', $teacher->email);
            Session::put('role', 'teacher');
            return redirect('teacher/home');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function forgotPassTeacher()
    {
        return view('teacher.auth.forgotPass');
    }

    public function resetCheckTeacher(Request $request)
    {
        $email = $request->email;
        $name = $request->name;

        $teacher = Teacher::where('email', '=', $email)
            ->where('name', '=', $name)->first();

        if ($teacher) {
            return redirect('teacher/newPass/' . $teacher->id);
        } else {
            return redirect()->back()->with('error', 'Email/Name does not match');
        }
    }

    public function newPassTeacher($teacherId)
    {
        $teacher = Teacher::find($teacherId);
        $resetCode = rand(100000, 999999);

        return view('teacher.auth.updatePass', compact('teacher', 'resetCode'));
    }

    public function updatePassTeacher(Request $request, $teacherId, $resetCode)
    {
        $teacher = Teacher::find($teacherId);
        $pass = $request->password;
        $confirmPass = $request->confirmPassword;
        $resetCodeInput = $request->resetCode;

        if ($pass == $confirmPass && $resetCodeInput == $resetCode) {
            $teacher->password = $pass;
            $teacher->save();
            return redirect('teacher/login')->with('success', 'Password updated');
        } else {
            return redirect()->back()->with('error', 'Passwords/Reset code do not match');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
