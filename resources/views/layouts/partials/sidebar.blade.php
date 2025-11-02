<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    * {
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.875rem;
    }

    body {
        overflow-y: auto;
    }

    a,
    a:hover,
    a:focus,
    a:active {
        text-decoration: none;
        outline: none;
        font-family: 'Poppins', sans-serif;
    }

    li {
        list-style: none;
    }

    .wrapper {
        display: flex;
        min-height: 100vh;
        width: auto;
    }

    #sidebar {
        width: 264px;
        background: var(--bs-dark);
        color: #fff;
        overflow-y: auto;
        transition: margin 0.35s ease-in-out;
    }

    .main {
        flex-grow: 1;
        background: #e9ecef;
        padding: 1rem;
    }

    .sidebar-logo {
        padding: 0.75rem;
        background-color: #ddd2b1;
    }

    .sidebar-logo a {
        display: block;
        color: #000;
        font-weight: 900;
        padding: 5px;
        background-color: #94c6dc;
        border: 2px solid #000;
        border-radius: 5px;
        text-align: center;
    }

    .sidebar-nav {
        padding: 0;
        margin: 0;
    }

    .sidebar-item .sidebar-link {
        padding: 0.625rem 1.625rem;
        display: block;
        color: #e9ecef;
        position: relative;
        font-size: 0.875rem;
    }

    .sidebar-link[data-bs-toggle="collapse"]::after {
        content: "";
        border: solid;
        border-width: 0 .075rem .075rem 0;
        display: inline-block;
        padding: 2px;
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%) rotate(-135deg);
        transition: transform 0.2s;
    }

    .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
        transform: translateY(-50%) rotate(45deg);
    }

    #sidebar.collapsed {
        margin-left: -264px;
    }

    @media (max-width: 767.98px) {
        #sidebar {
            margin-left: -264px;
        }

        #sidebar.collapsed {
            margin-left: 0;
        }

        .navbar,
        footer {
            width: 100vw;
        }
    }

    .theme-toggle {
        position: fixed;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        z-index: 10;
    }

    .theme-toggle i {
        padding: 10px;
        font-size: 1.25rem;
        color: #FFF;
        cursor: pointer;
    }

    .white-divider {
        height: 1px;
        background-color: #ffffff;
    }
</style>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar shadow-lg">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#dashboard" aria-expanded="false">
                            <i class="fa-solid fa-gauge"></i> Dashboard
                        </a>
                        <ul id="dashboard" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('admin.dashboard') }}"
                                    class="sidebar-link">Admin</a></li>
                            <li class="sidebar-item"><a href="#" class="sidebar-link">Student</a></li>
                            <li class="sidebar-item"><a href="#" class="sidebar-link">Parent</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#students" aria-expanded="false">
                            <i class="fa-solid fa-user-graduate"></i> Students
                        </a>
                        <ul id="students" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('allstudent.index') }}" class="sidebar-link">All
                                    Students</a></li>
                            <li class="sidebar-item"><a href="#" class="sidebar-link">Student Details</a></li>
                            <li class="sidebar-item"><a href="{{ route('register.student.and.parent') }}"
                                    class="sidebar-link">Admit Form</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#teachers" aria-expanded="false">
                            <i class="fa-solid fa-chalkboard-teacher"></i> Teachers
                        </a>
                        <ul id="teachers" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('allteachers.index') }}" class="sidebar-link">All
                                    Teachers</a></li>
                            <li class="sidebar-item"><a href="{{ route('addteacher.create') }}" class="sidebar-link">Add
                                    Teacher</a></li>
                        </ul>
                    </li>
                    {{-- <li class="sidebar-item">
                        <a href="{{ route('allparents.index') }}" class="sidebar-link shadow">
                            <i class="fa-solid fa-user-group"></i> Parents
                        </a>
                    </li> --}}
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#library" aria-expanded="false">
                            <i class="fa-solid fa-book"></i> Library
                        </a>
                        <ul id="library" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="allbooks" class="sidebar-link">All Books</a></li>
                            <li class="sidebar-item"><a href="addbooks" class="sidebar-link">Add Books</a></li>
                        </ul>
                    </li> --}}
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#marks" aria-expanded="false">
                            <i class="fa-solid fa-chart-bar"></i> Grades & Results
                        </a>
                        <ul id="marks" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{ route('grades.create') }}" class="sidebar-link">
                                    Add Grade
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('grades.index') }}" class="sidebar-link">
                                    View Grades
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">
                                    Show Result
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#classes" aria-expanded="false">
                            <i class="fa-solid fa-school"></i> Class
                        </a>
                        <ul id="classes" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{ route('classes.index') }}" class="sidebar-link">
                                    All Classes
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('classes.create') }}" class="sidebar-link">
                                    Add Class
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('departments.create') }}" class="sidebar-link">
                                    Add Department
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('departments.index') }}" class="sidebar-link">
                                    View Departments
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#subjects" aria-expanded="false">
                            <i class="fa-solid fa-book-open"></i> Subjects
                        </a>
                        <ul id="subjects" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{ route('subjects.index') }}" class="sidebar-link">
                                    Add Subject
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('coursedistribution.index') }}" class="sidebar-link">
                                    Course Distribution
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('creditdistribution.index') }}" class="sidebar-link">
                                    Credit Distribution
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('prerequisite.index') }}" class="sidebar-link">
                                    Pre Requisite
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('courseoverview.index') }}" class="sidebar-link">
                                    Course Overview
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('courseoffering.index') }}" class="sidebar-link">
                                    Course Offering
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#classRoutineMenu" aria-expanded="false">
                            <i class="fa-solid fa-calendar-days"></i> Class Routine
                        </a>
                        <ul id="classRoutineMenu" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{ route('classroutines.index') }}" class="sidebar-link">
                                    View Class Routines
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('classroutines.create') }}" class="sidebar-link">
                                    Add Class Routine
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-calendar-check"></i> Attendance
                    </a>
                </li> --}}
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#exam" aria-expanded="false">
                            <i class="fa-solid fa-graduation-cap"></i> Exam
                        </a>
                        <ul id="exam" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('examschedule.index') }}"
                                    class="sidebar-link">Add
                                    Exam Schedule</a></li>
                            <li class="sidebar-item"><a href="{{ route('examschedule.list') }}"
                                    class="sidebar-link">View Exam Schedule</a></li>
                        </ul>
                    </li>
                    {{-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-bullhorn"></i> Notices
                    </a>
                </li> --}}
                    {{-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-envelope"></i> Messages
                    </a>
                </li> --}}
                    {{-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-map-marker-alt"></i> Maps
                    </a>
                </li> --}}
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#creditRequirement" aria-expanded="false">
                            <i class="fa-solid fa-coins"></i> Credit Requirement
                        </a>
                        <ul id="creditRequirement" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{ route('creditperyear.create') }}" class="sidebar-link">Set Credit</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('creditperyear.index') }}" class="sidebar-link">View Credit</a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </div>
        </aside>
        <div>
            <button class="btn shadow-lg mt-2" id="sidebar-toggle" type="button"
                style="position:relative;left:27%;border: 2px solid #ffc107;" title="Toggle sidebar"
                aria-label="Toggle sidebar navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
    <!-- JavaScript for Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebar-toggle');

            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        });
    </script>
</body>
