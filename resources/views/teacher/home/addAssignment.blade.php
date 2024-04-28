<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Assignment</title>
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
        <form action="{{ url('teacher/store-assignment/'.$course->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <h2>Add Assignment - {{ $course->title }}</h2>
            <div class="form-group">
                <label for="file" class="text-info">Question File:</label><br>
                <input type="file" name="file" id="file" class="form-control" multiple >
            </div>
            <div class="form-group">
                <label for="submission_date" class="text-info">Submission Date:</label><br>
                <input type="date" name="submission_date" id="submission_date" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-info btn-md" value="Save">
            </div>
        </form>

        <br><br><br><br>

        @include('shared.footer')
    </div>


</body>

</html>