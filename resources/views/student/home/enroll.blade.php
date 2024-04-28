<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Course</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    @include('shared.student-nav')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Enroll Courses') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ url('student/storeCourse') }}">
                            @csrf

                            @foreach($courses as $course)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="course_{{ $course->id }}" name="courses[]" value="{{ $course->id }}">
                                <label class="form-check-label" for="course_{{ $course->id }}">{{ $course->title }}</label>
                            </div>
                            @endforeach

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><br>

                <div class="card">
                    <div class="card-header">{{ __('Enrollments') }}</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <th>Course Title</th>
                                <th>Course Description</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach($studentCourses as $c)
                                <tr>
                                    <td>{{ $c->title }}</td>
                                    <td>{{ $c->description }}</td>
                                    <td>
                                        <a href="{{ url('student/delete-course/'.$c->id) }}" class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$c->id}}">Remove</a>

                                        <div class="modal" id="myModal{{$c->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Delete Confirmation</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        Are you sure you want to delete <b>{{ $c->title }} </b> ?
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="{{ url('student/delete-course/'.$c->id) }}" class="btn btn-danger">Yes</a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <br><br><br><br>

    @include('shared.footer')
</body>

</html>