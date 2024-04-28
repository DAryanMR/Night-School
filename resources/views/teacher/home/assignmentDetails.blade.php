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

    @include('shared.teacher-nav')

    <div class="container">
        @if(Session::has('error'))
        <div class="alert alert-danger">
            <span>{{ Session::get('error') }}</span>
        </div>
        @endif
        <div class="card">
            <div class="card-header">{{ __('Assignment Details') }}</div>
            <div class="card-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>File</th>
                        <th>Remarks</th>
                        <th>Feedback</th>
                    </thead>
                    <tbody>
                        @foreach($assignmentFiltered as $a)
                        <tr>
                            <td>{{ $a['student_id'] }}</td>
                            <td>{{ $a['student_name'] }}</td>
                            <td>
                                <a href="{{ url('teacher/download-assignment-solution/'.$a['id']) }}">Download</a>
                            </td>
                            <td>
                                @if($a['remarks'])
                                {{ $a['remarks'] }}
                                @else
                                <a href="{{ url('teacher/mark-assignment/'.$a['id']) }}" data-toggle="modal" data-target="#myModal{{$a['id']}}">Give Marks</a>
                                <!-- The Modal -->
                                <div class="modal" id="myModal{{$a['id']}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Mark Assignment </h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{ url('teacher/mark-assignment/'.$a['id']) }}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="remarks" class="text-info">Remarks:</label><br>
                                                        <input type="number" name="remarks" id="remarks" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" name="submit" class="btn btn-info btn-md" value="Save">
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <!-- <a href="{{ url('teacher/mark-assignment/'.$a['id']) }}" class="btn btn-success">Save</a> -->
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($a['feedback'])
                                {{ $a['feedback'] }}
                                @else
                                <a href="{{ url('teacher/give-feedback/'.$a['id']) }}" data-toggle="modal" data-target="#myModalF{{$a['id']}}">Give Feedback</a>
                                <!-- The Modal -->
                                <div class="modal" id="myModalF{{$a['id']}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Give Feedback </h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{ url('teacher/give-feedback/'.$a['id']) }}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="feedback" class="text-info">Feedback:</label><br>
                                                        <input type="text" name="feedback" id="feedback" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" name="submit" class="btn btn-info btn-md" value="Save">
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    @include('shared.footer')

</body>

</html>