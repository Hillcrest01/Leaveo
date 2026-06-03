<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background-color: #FFFFFF;
            }
            
            .btn-primary {
                background-color: #F15929;
                border-color: #F15929;
            }
            
            .btn-primary:hover {
                background-color: #231F20;
                border-color: #231F20;
            }
            
            .form-check-input:checked {
                background-color: #F15929;
                border-color: #F15929;
            }
            
            .form-check-input:focus {
                border-color: #F7AB93;
                box-shadow: 0 0 0 0.2rem rgba(241, 89, 41, 0.25);
            }
            
            .form-control:focus {
                border-color: #F7AB93;
                box-shadow: 0 0 0 0.2rem rgba(241, 89, 41, 0.25);
            }
            
            a {
                color: #F15929;
                text-decoration: none;
            }
            
            a:hover {
                color: #231F20;
                text-decoration: underline;
            }
            
            .card {
                border: none;
            }
            
            .text-primary-custom {
                color: #F15929;
            }
            
            .bg-primary-custom {
                background-color: #F15929;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
            <div class="row w-100 justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="text-center mb-4">
    <a href="/" class="text-decoration-none d-inline-block">
        {{-- <img src="{{ asset('storage/img/dsl.png') }}" alt="DSL Systems & Solutions Ltd" class="d-block mx-auto mb-2" style="width: 70px; height: 70px; object-fit: contain;"> --}}
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 d-block mx-auto mb-2" style="width: 70px; height: 70px; object-fit: contain;" />
        <span class="fw-semibold d-block" style="color: #F15929; font-size: 1rem;">
            Leaveo - Giving you the best with less effort
        </span>
    </a>
</div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>