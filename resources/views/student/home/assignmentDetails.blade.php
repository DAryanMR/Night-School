<!DOCTYPE html>
<html lang="en">

<head>
    <title>Assignments Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    @include('shared.student-nav')

    <div class="container">
        <div class="card">
            <div class="card-header">{{ __('Assignment Details') }}</div>
            <div class="card-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>File</th>
                        <th>Remarks</th>
                        <th>Feedback</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $course_name }}</td>
                            <td>{{ $teacher_name }}</td>
                            @if($student_assignment)
                            <td>
                                <a href="{{ url('student/download-assignment-solution/'.$student_assignment->id) }}">Download</a>
                            </td>
                            <td>{{ $student_assignment->remarks }}</td>
                            <td>{{ $student_assignment->feedback }}</td>
                            @endif
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <br><br><br><br>

    @include('shared.footer')

</body>

</html>