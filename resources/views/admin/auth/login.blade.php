<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.PNG') }}">
    <title>Admin Login</title>

    <link href="{{ asset('vendor/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('vendor/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .login-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        body.bg-gradient-primary {
            background-color: #010064;
            background-image: linear-gradient(180deg, #010064 10%, #02004f 100%);
        }
        .logo-column {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            min-height: 450px;
        }
        .btn-primary {
            background-color: #010064;
            border-color: #010064;
        }
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #02004f;
            border-color: #02004f;
        }
        .form-control:focus {
            border-color: #010064;
            box-shadow: 0 0 0 0.2rem rgba(1, 0, 100, 0.25);
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5 login-card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block logo-column">
    <div class="text-center">
        <img src="{{ asset('vendor/img/logo.png') }}" alt="Logo" class="img-fluid" style="max-width:400px;">
    </div>
</div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat Datang Admin</h1>
                                    </div>

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form class="user" method="POST" action="{{ route('admin.login.store') }}">
                                        @csrf

                                        <div class="form-group">
                                            <input type="email"
                                                class="form-control form-control-user @error('email') is-invalid @enderror"
                                                name="email"
                                                value="{{ old('email') }}"
                                                placeholder="Masukkan email"
                                                required
                                                autofocus>
                                        </div>

                                        <div class="form-group">
                                            <input type="password"
                                                class="form-control form-control-user @error('password') is-invalid @enderror"
                                                name="password"
                                                placeholder="Password"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox"
                                                    class="custom-control-input"
                                                    id="remember"
                                                    name="remember"
                                                    value="1">
                                                <label class="custom-control-label" for="remember">Ingat saya</label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Masuk
                                        </button>
                                    </form>

                                    <hr>
                                    <div class="text-center">
                                        <a class="small text-white" href="#">Lupa password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
