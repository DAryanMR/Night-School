<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    @include('shared.teacher-nav')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add Attendance') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ url('attendance/store/'.$courseId) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="duration" class="col-md-4 col-form-label text-md-right">{{ __('Num of Classes (in days)') }}</label>

                                <div class="col-md-6">
                                    <select id="duration" class="form-control" name="duration" onchange="generateScheduleFields(this.value)" required>
                                        <option value="">Select Duration</option>
                                        <option value="1">1 Day</option>
                                        <option value="2">2 Days</option>
                                        <option value="3">3 Days</option>
                                        <option value="4">4 Days</option>
                                        <option value="5">5 Days</option>
                                        <option value="6">6 Days</option>
                                        <option value="7">7 Days</option>
                                        <option value="8">8 Days</option>
                                        <option value="9">9 Days</option>
                                        <option value="10">10 Days</option>
                                    </select>
                                </div>
                            </div>

                            <div id="schedule_fields"></div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br><br><br>


    @include('shared.footer')


    <script>
        function generateScheduleFields(duration) {
            var scheduleFields = document.getElementById('schedule_fields');
            scheduleFields.innerHTML = '';

            if (duration > 0) {
                for (var i = 1; i <= duration; i++) {
                    var scheduleFieldGroup = document.createElement('div');
                    scheduleFieldGroup.className = 'form-group row';
                    scheduleFields.appendChild(scheduleFieldGroup);

                    var dateLabel = document.createElement('label');
                    dateLabel.className = 'col-md-4 col-form-label text-md-right';
                    dateLabel.textContent = 'Class ' + i;
                    scheduleFieldGroup.appendChild(dateLabel);

                    var dateInputDiv = document.createElement('div');
                    dateInputDiv.className = 'col-md-6';
                    scheduleFieldGroup.appendChild(dateInputDiv);

                    var dateInput = document.createElement('input');
                    dateInput.id = 'date_' + i;
                    dateInput.type = 'date';
                    dateInput.className = 'form-control';
                    dateInput.name = 'date_' + i;
                    dateInput.required = true;
                    dateInputDiv.appendChild(dateInput);


                }
            }
        }
    </script>

</body>

</html>