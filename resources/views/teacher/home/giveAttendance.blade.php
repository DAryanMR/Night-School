<!DOCTYPE html>
<html lang="en">

<head>
    <title>Give Attendance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    @include('shared.teacher-nav')

    <div class="jumbotron">
        <h2>Give Attendance</h2>
        <form action="{{ url('teacher/storeAttendance/'.$attendanceId) }}" method="post">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mergedArray as $enrolment)
                    <tr>
                        <td>{{ $enrolment['date'] }}</td>
                        <td>{{ $enrolment['student_id'] }}</td>
                        <td>{{ $enrolment['student_name'] }}</td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="attendance[{{ $enrolment['student_id'] }}][{{ $enrolment['attendance_id'] }}]" value="present">
                                <label class="form-check-label">Present</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="attendance[{{ $enrolment['student_id'] }}][{{ $enrolment['attendance_id'] }}]" value="absent">
                                <label class="form-check-label">Absent</label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-info btn-md" value="Submit Attendance">
            </div>
        </form>
    </div>


</body>

</html>