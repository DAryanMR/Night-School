<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password - Teacher</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <!-- Brand/logo -->
        <a class="navbar-brand" href="/">Night School</a>
    </nav>

    <div id="login">
        <h3 class="text-center text-info pt-5">Password reset - {{ $teacher->name }}</h3>
        <h5 class="text-center text-info pt-5">Reset Code - {{ $resetCode }}</h5>
        <div class="container">
            @if (Session::has('error'))
            <div class="alert alert-danger">
                <strong>{{ Session::get('error') }}</strong>
            </div>
            @endif
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="{{ url('teacher/updatePass/' . $teacher->id . '/' . $resetCode) }}" method="post">
                            @csrf
                            <h3 class="text-center text-info">Enter new password</h3>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword" class="text-info">Confirm Password:</label><br>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="resetCode" class="text-info">Reset Code:</label><br>
                                <input type="number" name="resetCode" id="resetCode" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                                <a href="{{ url('teacher/forgot-password') }}">Forgot Password?</a>
                            </div>
                            <div id="register-link" class="text-right">
                                <a href="{{ url('teacher/register') }}" class="text-info">Register here</a>
                                <a href="{{ url('/student/login') }}" class="text-info">Student panel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br><br><br>

    @include('shared.footer')
</body>

</html>