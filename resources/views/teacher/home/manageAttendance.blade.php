<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Class Schedules</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body>
    @include('shared.teacher-nav')

    <div class="container">


        @if(Session::has('success'))
        <div class="alert alert-success">
            <strong>{{ Session::get('success') }}</strong>
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger">
            <strong>{{ Session::get('error') }}</strong>
        </div>
        @endif
        <h2>Manage Attendance</h2>



        <table class="table table-bordered table-sm">
            <thead>
                <th>Date</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach($teacher_courses_filtered as $c)
                <tr>
                    <td>{{ $c['date'] }}</td>
                    <td>
                        <a href="{{ url('teacher/give-attendance/'.$c['id']) }}">Manage</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <h2>View Attendance</h2>
        <table class="table table-bordered table-sm">
            <thead>
                <th>Date</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course Name</th>
                <th>Attendance</th>
            </thead>
            <tbody>
                @foreach($attendanceSheet as $a)
                <tr>
                    <td>{{ $a->date }}</td>
                    <td>{{ $a->student_id }}</td>
                    <td>{{ $a->student_name }}</td>
                    <td>{{ $a->course_name }}</td>
                    <td>{{ $a->attendance }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br><br><br><br>

        @include('shared.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>