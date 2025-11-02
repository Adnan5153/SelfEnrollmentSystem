<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm ">
    <div class="container-fluid">
        <!-- Brand Logo -->
        <a class="navbar-brand fw-bold" href="#" style="">
            <span style="color: #20c997; font-size: 1.5rem;">CIU</span>Teacher Management System
        </a>

        <!-- Toggler for Mobile View -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Right-Side Icons and Dropdown -->
            <ul class="navbar-nav ms-auto">
                <!-- Notifications -->
                <li class="nav-item">
                    <button type="button" class="btn btn-link text-dark">
                        <i class="fa-solid fa-envelope fa-lg"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-link text-dark">
                        <i class="fa-solid fa-bell fa-lg"></i>
                    </button>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-circle fa-lg"></i> Teacher
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('teacher.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
