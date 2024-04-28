<!DOCTYPE html>
<html lang="en">

<head>
    <title>Assignments</title>
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
            <div class="card-header">{{ __('Assignments') }}</div>
            <div class="card-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>File</th>
                        <th>Submission date</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach($student_assignments as $c)
                        <tr>
                            <td>{{ $c['course_name'] }}</td>
                            <td>{{ $c['teacher_name'] }}</td>
                            <td>
                                <a href="{{ url('student/download-assignment/'.$c['id']) }}">Download</a>
                            </td>
                            <td>{{ $c['submission'] }}</td>
                            <td>
                                <a href="{{ url('student/upload-assignment/'.$c['id']) }}" class="btn btn-primary">Submit</a>
                                <a href="{{ url('student/assignment-details/'.$c['id']) }}" class="btn btn-info">Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <br><br><br><br>

    @include('shared.footer')


</body>

</html>