<!DOCTYPE html>
<html lang="en">

<head>
    <title>Attendance Menu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    @include('shared.teacher-nav')

    <div class="container">
        <h2>Attendance Menu</h2>
        <table class="table table-bordered table-sm">
            <thead>
                <th>Course Name</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($teacher_courses_filtered as $c)
                <tr>
                    <td>{{ $c['course_title'] }}</td>
                    <td>
                        <a href="{{ url('teacher/add-attendance/'.$c['course_id']) }}" class="btn btn-secondary">Add Attendance</a>
                        <a href="{{ url('teacher/manage-attendance/'.$c['course_id']) }}" class="btn btn-info">Manage Attendance</a>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>

        @include('shared.footer')
    </div>

    <br><br><br><br>

</body>

</html>