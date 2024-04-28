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
        <h2>All Class Schedules</h2>
        <table class="table table-bordered table-sm">
            <thead>
                <th>Course Name</th>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($class_schedules as $c)
                <tr>
                    <td>{{ $map_schedules[$c->course_id] }}</td>
                    <td>{{ $c->day_of_week }}</td>

                    @if(explode(":", $c->start_time)[0] >= 12)
                    <td>
                        {{$c->start_time}} PM
                    </td>
                    @else
                    <td>
                        {{$c->start_time}} AM
                    </td>
                    @endif
                    <!-- <td>{{ $c->start_time }}</td> -->
                    <!-- <td>{{ $c->end_time }}</td> -->

                    @if(explode(":", $c->end_time)[0] >= 12)
                    <td>
                        {{$c->end_time}} PM
                    </td>
                    @else
                    <td>
                        {{$c->end_time}} AM
                    </td>
                    @endif
                    <td>
                        <a href="{{ url('teacher/edit-routine/'.$c->id) }}" class="btn btn-secondary">Update Routine</a>
                        <a href="{{ url('teacher/delete-routine/'.$c->id) }}" class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$c->id}}">Delete Routine</a>

                        <div class="modal" id="myModal{{$c->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Confirmation</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        Are you sure you want to delete
                                        <b>{{ $map_schedules[$c->course_id] }}
                                            {{ $c->day_of_week }} {{ $c->start_time }} {{ $c->end_time }}
                                        </b> ?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a href="{{ url('teacher/delete-routine/'.$c->id) }}" class="btn btn-danger">Yes</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>
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