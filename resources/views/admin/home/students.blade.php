<!DOCTYPE html>
<html lang="en">

<head>
    <title>Students</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    @include('shared.admin-nav')

    <div class="container">


        <div class="row py-2">
            <div class="col-md-6">
                <h2>Students</h2>

            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <form method="get" action="{{ url('admin/search-student') }}">
                        <div class="input-group">
                            <input name="search_student" value="{{ isset($search) ? $search : '' }}" placeholder="Search student info" class="form-control">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        <table class="table table-striped">
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($students as $s)
                <tr>
                    <td>{{ $s->name }}</td>
                    <td>{{ $s->email }}</td>
                    <td>
                        <a href="{{ url('admin/edit-student/'.$s->id) }}" class="btn btn-secondary">Edit</a>
                        <a href="{{ url('admin/delete-student/'.$s->id) }}" data-toggle="modal" data-target="#myModal{{$s->id}}" class="btn btn-danger">Delete</a>
                        <!-- The Modal -->
                        <div class="modal" id="myModal{{$s->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Confirmation </h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        Are you sure you want to delete <b>{{ $s->name }}</b>?
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a href="{{ url('admin/delete-student/'.$s->id) }}" class="btn btn-danger">Yes</a>
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

    @include('shared.footer')

</body>

</html>