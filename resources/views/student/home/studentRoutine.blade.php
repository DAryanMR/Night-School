<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Routine</title>
    <style>
        .overlap-row {
            border: 2px solid red;
            color: red;
        }
    </style>
</head>

<body>
    @include('shared.student-nav')

    <div class="container">
        <h2>Class Routine</h2>

        @foreach ($routine as $dayOfWeek => $courses)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ $dayOfWeek }}</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                @if($course['overlap'])
                <tr class="overlap-row">
                    <td>{{$course['courseName']}}</td>

                    @if(explode(":", $course['startTime'])[0] >= 12)
                    <td>
                        {{$course['startTime']}} PM
                    </td>
                    @else
                    <td>
                        {{$course['startTime']}} AM
                    </td>
                    @endif

                    @if(explode(":", $course['endTime'])[0] >= 12)
                    <td>
                        {{$course['endTime']}} PM
                    </td>
                    @else
                    <td>
                        {{$course['endTime']}} AM
                    </td>
                    @endif
                </tr>
                @else
                <tr>
                    <td>{{$course['courseName']}}</td>

                    @if(explode(":", $course['startTime'])[0] >= 12)
                    <td>
                        {{$course['startTime']}} PM
                    </td>
                    @else
                    <td>
                        {{$course['startTime']}} AM
                    </td>
                    @endif

                    @if(explode(":", $course['endTime'])[0] >= 12)
                    <td>
                        {{$course['endTime']}} PM
                    </td>
                    @else
                    <td>
                        {{$course['endTime']}} AM
                    </td>
                    @endif
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        @endforeach

        <br><br><br><br>
    </div>

    @include('shared.footer')
</body>

</html>