<!DOCTYPE html>
<html lang="en">

<head>
    <title>Teacher - Home</title>
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
        <div class="row">
            <div class="col-sm-4">
                <h3><a href="{{ url('teacher/assignments') }}">Assignments</a></h3>
                <p>Manage assignments</p>
            </div>
            <div class="col-sm-4">
                <h3><a href="{{ url('teacher/attendance-menu') }}">Attendance</a></h3>
                <p>Manage attendance</p>
            </div>
            <div class="col-sm-4">
                <h3><a href="{{ url('teacher/class-routine') }}">Class Routine</a></h3>
                <p>Manage routines</p>
            </div>
        </div>
    </div>

    @include('shared.footer')

</body>

</html>