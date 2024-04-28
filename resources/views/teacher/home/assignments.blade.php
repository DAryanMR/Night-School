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
        <h2>Manage Assignments</h2>

        <table class="table table-bordered table-sm">
            <thead>
                <th>Course Name</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($courses as $c)
                <tr>
                    <td>{{ $c->title }}</td>
                    <td>
                        <a href="{{ url('teacher/add-assignment/'.$c->id) }}" class="btn btn-secondary">Add Assignment</a>
                        <a href="{{ url('teacher/assignment-details/'.$c->id) }}" class="btn btn-info">Details</a>
                        <a href="{{ url('teacher/delete-assignment/'.$c->id) }}" data-toggle="modal" data-target="#myModal{{$c->id}}" class="btn btn-danger">Delete</a>
                        <!-- The Modal -->
                        <div class="modal" id="myModal{{$c->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Confirmation </h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        Are you sure you want to delete <b>{{ $c->title }}</b>?
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a href="{{ url('teacher/delete-assignment/'.$c->id) }}" class="btn btn-danger">Yes</a>
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

    <br><br><br><br>

    @include('shared.footer')

</body>

</html>