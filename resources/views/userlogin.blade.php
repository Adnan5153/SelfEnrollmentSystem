<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LBS Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('{{ asset('images/logohome.png') }}');
            background-size: 90%;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
    @yield('styles')

</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="">
            @if (Route::has('login'))

                @auth('web')
                    <a href="{{ url('/dashboard') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        role="button">
                        Dashboard
                    </a>
                @else
                    <!-- <a
                                            href="{{ route('login') }}"
                                            class="btn btn-primary" role="button">
                                            User Log in
                                        </a> -->

                    <!-- @if (Route::has('register'))
    <a
                                            href="{{ route('register') }}"
                                            class="btn btn-primary" role="button">
                                            Register
                                        </a>
    @endif -->
                @endauth

                @auth('admin')
                    <a href="{{ url('/admin/dashboard') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                        Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('admin.login') }}" class="btn text-white"
                        style="background-color: var(--bs-purple); border-color: var(--bs-purple);"
                        onmouseover="this.style.backgroundColor='#5a35a0'; this.style.borderColor='#5a35a0';"
                        onmouseout="this.style.backgroundColor='var(--bs-purple)'; this.style.borderColor='var(--bs-purple)';">
                        Admin Log in
                    </a>



                    <!-- @if (Route::has('admin.register'))
    <a
                                            href="{{ route('admin.register') }}"
                                            class="btn btn-primary" role="button">
                                            Admin Register
                                        </a>
    @endif -->
                @endauth

                @auth('teacher')
                    <a href="{{ url('/teacher/dashboard') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                        Teacher Dashboard
                    </a>
                @else
                    <a href="{{ route('teacher.login') }}" class="btn text-white"
                        style="background-color: var(--bs-purple); border-color: var(--bs-purple);"
                        onmouseover="this.style.backgroundColor='#5a35a0'; this.style.borderColor='#5a35a0';"
                        onmouseout="this.style.backgroundColor='var(--bs-purple)'; this.style.borderColor='var(--bs-purple)';">
                        Teacher Log in
                    </a>



                    <!-- @if (Route::has('teacher.register'))
    <a
                                            href="{{ route('teacher.register') }}"
                                            class="btn btn-primary" role="button">
                                            Teacher Register
                                        </a>
    @endif -->
                @endauth

                @auth('student')
                    <a href="{{ url('/student/dashboard') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                        Student Dashboard
                    </a>
                @else
                    <a href="{{ route('student.login') }}" class="btn text-white"
                        style="background-color: var(--bs-purple); border-color: var(--bs-purple);"
                        onmouseover="this.style.backgroundColor='#5a35a0'; this.style.borderColor='#5a35a0';"
                        onmouseout="this.style.backgroundColor='var(--bs-purple)'; this.style.borderColor='var(--bs-purple)';">
                        Student Log in
                    </a>


                    <!-- @if (Route::has('student.register'))
    <a
                                            href="{{ route('student.register') }}"
                                            class="btn btn-primary" role="button">
                                            student Register
                                        </a>
    @endif -->
                @endauth

            @endif
        </div>
    </div>

    <main class="mt-6">

    </main>

    <footer class="py-16 text-center text-sm text-black dark:text-white/70">

    </footer>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
