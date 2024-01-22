<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <title>Login | {{ config('app.name') }}</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/front-style.css') }}" />
    <style>
        .hero {
            background-color: #1c4899;
            padding: 60px 0 0px;
            background-size: cover;
            width: 100%;
            /* overflow: hidden; */
            background-image: url({{ asset('assets/images/heros/hero-1-bg.png') }});
        }
    </style>
</head>

<body class="hero">
    <!-- home-agency start -->
    <section style="padding-top: 0px !important;padding-bottom: 0px !important;">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-lg-7">
                    <p class="hero-title fw-bold fs-3 text-success mt-4" style="color: #b4a04e !important">
                        Happy Royal E-Library
                    </p>

                    <p class="text-white opacity-75 fs-8 mb-3">
                        Welcome back to the Happy Royal E-Library! Please log in to your account and unlock a world of
                        imagination. Your next reading adventure awaits – let's get started together!
                    </p>

                    <div class="text-center mt-3 mb-4 d-lg-block d-none">
                        <img width="50%" src="{{ asset('assets/images/password.png') }}" alt="{{ config('app.name') }}"
                            class="img-fluid" />
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card mb-4" style="border-radius: 0% !important">
                        <div class="card-body p-4">
                            <div class="mb-3 mt-0">
                                <img width="180px" src="{{ asset('assets/images/logo.png') }}" alt=""
                                    class="logo-light" />
                            </div>
                            <hr>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row">
                                    <div class="mb-3">
                                        <span style="font-size: 14px" class="form-label fw-bold required">Student
                                            ID</span>
                                        <input name="studentId" value="{{ old('studentId') }}" required type="text"
                                            class="@error('studentId') is-invalid @enderror form-control form-input">

                                        @error('studentId')
                                            <span style="font-size: 13px" class="form-label fw-bolder text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <span style="font-size: 14px" class="form-label fw-bold required">
                                            Password</span>
                                        <input name="password" required type="password"
                                            class="@error('password') is-invalid @enderror form-control form-input">
                                        @error('password')
                                            <span style="font-size: 13px" class="form-label fw-bolder text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100"
                                    style="background-color: #1c4899 !important">Login</button>
                                <p class="mt-2 text-center">Don't have an account? <a href="{{ route('register') }}"
                                        class="text-primary">Sign Up</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
