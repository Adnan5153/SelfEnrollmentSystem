<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    *,
    ::after,
    ::before {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        font-size: 0.875rem;
        opacity: 1;
        overflow-y: scroll;
        margin: 0;
    }

    #sidebar a,
    #sidebar a:hover,
    #sidebar a:focus,
    #sidebar a:active {
        text-decoration: none;
        /* Ensures no underline */
        outline: none;
        /* Removes focus outline that might appear as an underline */
    }

    a {
        cursor: pointer;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
    }

    li {
        list-style: none;
    }

    h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.275rem;
        color: var(--bs-emphasis-color);
    }

    /* Layout for admin dashboard skeleton */

    .wrapper {
        align-items: stretch;
        display: flex;

    }

    #sidebar {
        max-width: 264px;
        min-width: 264px;
        background: var(--bs-dark);
        transition: all 0.35s ease-in-out;
    }

    .main {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        min-width: 0;
        overflow: hidden;
        transition: all 0.35s ease-in-out;
        width: 100%;
        background: #e9ecef;

    }

    /* Sidebar Elements Style */

    .sidebar-logo {
        padding: 0.75rem;
        background-color: ddd2b1;
    }

    .sidebar-logo a {
        color: #000;
        font-size: 16.95px;
        font-weight: 900;
        margin: auto;
        background-color: #94c6dc;
        padding: 1px;
        border: 2px solid #000;
        /* Adjust the border size and color as needed */
        border-radius: 5px;
        /* Optional: adds rounded corners to the border */


    }

    .sidebar-nav {
        list-style: none;
        margin-bottom: 0;
        padding-left: 0;
        margin-left: 0;
    }

    .sidebar-header {
        color: #e9ecef;
        font-size: .75rem;
        padding: 1.5rem 1.5rem .375rem;
    }

    a.sidebar-link {
        padding: .625rem 1.625rem;
        color: #e9ecef;
        position: relative;
        display: block;
        font-size: 0.875rem;
    }

    .sidebar-link[data-bs-toggle="collapse"]::after {
        border: solid;
        border-width: 0 .075rem .075rem 0;
        content: "";
        display: inline-block;
        padding: 2px;
        position: absolute;
        right: 1.5rem;
        top: 1.4rem;
        transform: rotate(-135deg);
        transition: all .2s ease-out;
    }

    .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
        transform: rotate(45deg);
        transition: all .2s ease-out;
    }

    .avatar {
        height: 40px;
        width: 40px;
    }

    .navbar-expand .navbar-nav {
        margin-left: auto;
    }

    .content {
        flex: 1;
        max-width: 100vw;
        width: 100vw;
    }

    @media (min-width:768px) {
        .content {
            max-width: auto;
            width: auto;
        }
    }

    .card {
        box-shadow: 0 0 .875rem 0 rgba(34, 46, 60, .05);
        margin-bottom: 24px;
    }

    .illustration {
        background-color: var(--bs-primary-bg-subtle);
        color: var(--bs-emphasis-color);
    }

    .illustration-img {
        max-width: 150px;
        width: 100%;
    }

    /* Sidebar Toggle */

    #sidebar.collapsed {
        margin-left: -264px;
    }

    /* Footer and Nav */

    @media (max-width:767.98px) {

        .js-sidebar {
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

    /* Theme Toggler */

    .theme-toggle {
        position: fixed;
        top: 50%;
        transform: translateY(-65%);
        text-align: center;
        z-index: 10;
        right: 0;
        left: auto;
        border: none;
        /* background-color: var(--bs-body-color); */
    }


    html[data-bs-theme="dark"] .theme-toggle .fa-sun,
    html[data-bs-theme="light"] .theme-toggle .fa-moon {
        cursor: pointer;
        padding: 10px;
        display: block;
        font-size: 1.25rem;
        color: #FFF;
    }

    html[data-bs-theme="dark"] .theme-toggle .fa-moon {
        display: none;
    }

    html[data-bs-theme="light"] .theme-toggle .fa-sun {
        display: none;
    }

    .white-divider {
        height: 1px;

        overflow: hidden;
        background-color: #ffffff;
        /* Set the color to white */
    }
</style>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar shadow-lg" style="min-height: 100vh;">
            <!-- Content For Sidebar -->
            <div class="h-100">
                {{-- <div class="sidebar-logo d-flex align-items-center">
                    <img src="{{ asset('images/bird (1).png') }}" alt="Little Birds School Logo"
                        style="height: 50px; width: auto; margin-right: 10px;">
                    <a href="#">Little Birds School</a>
                </div> --}}
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#dashboard" aria-expanded="false">
                            <i class="fas fa-chart-bar"></i> Dashboard
                        </a>
                        <ul id="dashboard" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.dashboard') }}"
                                    class="sidebar-link">Dashboard</a></li>
                            <li class="sidebar-item"><a href="#" class="sidebar-link">Profile</a></li>
                        </ul>
                    </li>
                    <!-- Class Management -->
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#classManagement" aria-expanded="false">
                            <i class="fa-solid fa-layer-group"></i> Class Management
                        </a>
                        <ul id="classManagement" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.classes') }}" class="sidebar-link">Class
                                    List</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.students') }}"
                                    class="sidebar-link">Student List</a></li>
                        </ul>
                    </li>

                    <!-- Attendance -->
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#attendance" aria-expanded="false">
                            <i class="fa-solid fa-check-square"></i> Attendance
                        </a>
                        <ul id="attendance" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.attendance.take') }}"
                                    class="sidebar-link">Take Attendance</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.attendance.report') }}"
                                    class="sidebar-link">Attendance Report</a></li>
                        </ul>
                    </li> --}}

                    <!-- Assignments -->
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#assignments" aria-expanded="false">
                            <i class="fa-solid fa-book"></i> Assignments
                        </a>
                        <ul id="assignments" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.assignments.create') }}"
                                    class="sidebar-link">Create Assignment</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.assignments.manage') }}"
                                    class="sidebar-link">Manage Assignments</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.assignments.submissions') }}"
                                    class="sidebar-link">Student Submissions</a></li>
                        </ul>
                    </li> --}}

                    <!-- Exam Management -->
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#examManagement" aria-expanded="false">
                            <i class="fa-solid fa-graduation-cap"></i> Exam Management
                        </a>
                        <ul id="examManagement" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.exam.schedule') }}"
                                    class="sidebar-link">Exam Schedule</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.addmarks') }}"
                                    class="sidebar-link">Enter Marks</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.gradebook') }}"
                                    class="sidebar-link">Grade Book</a></li>
                        </ul>
                    </li>

                    <!-- Class Routine -->
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#classRoutine" aria-expanded="false">
                            <i class="fa-regular fa-clipboard"></i> Class Routine
                        </a>
                        <ul id="classRoutine" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.routine') }}" class="sidebar-link">Class
                                    Schedule</a></li>
                        </ul>
                    </li>

                    {{-- <!-- Student Progress -->
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#studentProgress" aria-expanded="false">
                            <i class="fa-solid fa-chart-line"></i> Student Progress
                        </a>
                        <ul id="studentProgress" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.progress') }}"
                                    class="sidebar-link">Student Performance</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.remarks') }}"
                                    class="sidebar-link">Remarks</a></li>
                        </ul>
                    </li> --}}

                    <!-- Messages -->
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#messages" aria-expanded="false">
                            <i class="fa-solid fa-envelope"></i> Messages
                        </a>
                        <ul id="messages" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.messages') }}"
                                    class="sidebar-link">Messages</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.announcements') }}"
                                    class="sidebar-link">Announcements</a></li>
                        </ul>
                    </li> --}}

                    <!-- Notice Board -->
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#noticeBoard" aria-expanded="false">
                            <i class="fa-solid fa-flag"></i> Notice Board
                        </a>
                        <ul id="noticeBoard" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.notice') }}"
                                    class="sidebar-link">Notice</a></li>
                        </ul>
                    </li> --}}

                    <!-- Resources -->
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed shadow" data-bs-toggle="collapse"
                            data-bs-target="#resources" aria-expanded="false">
                            <i class="fa-solid fa-folder-open"></i> Resources and Materials
                        </a>
                        <ul id="resources" class="sidebar-dropdown list-unstyled collapse ms-4"
                            data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a href="{{ route('teacher.resources.upload') }}"
                                    class="sidebar-link">Upload Materials</a></li>
                            <li class="sidebar-item"><a href="{{ route('teacher.resources.shared') }}"
                                    class="sidebar-link">Shared Materials</a></li>
                        </ul>
                    </li> --}}
                </ul>
            </div>
        </aside>

        <div>
            <button class="btn shadow-lg" id="sidebar-toggle" type="button"
                style="position:relative;left:27%;border: 2px solid #ffc107;" title="Toggle sidebar"
                aria-label="Toggle sidebar navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <!-- Main Content Wrapper -->
        <div class="main flex-fill">
            <main>
                <div class="container-fluid px-4">
                    @yield('content') <!-- This will be your addmarks form or any other content -->
                </div>
            </main>
        </div>
        <!-- <div class="main">
            <nav class="navbar navbar-expand">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>

            </nav>

             <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>

        </div> -->
    </div>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector("#sidebar");
            const sidebarToggle = document.querySelector("#sidebar-toggle");
            const allSidebarLinks = document.querySelectorAll('.sidebar-link'); // Select all sidebar links

            // Restore the sidebar collapsed state from local storage
            const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            sidebar.classList.toggle("collapsed", isSidebarCollapsed);

            // Toggle sidebar on click and update local storage
            sidebarToggle.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains("collapsed"));
            });

            // Initialize all collapse components manually to better control them
            const collapseElements = document.querySelectorAll('.collapse');
            const bsCollapses = Array.from(collapseElements).map(el => new bootstrap.Collapse(el, {
                toggle: false // Do not toggle on initialization
            }));

            // Restore dropdown state from local storage and attach event listeners
            document.querySelectorAll('.sidebar-link[data-bs-toggle="collapse"]').forEach(link => {
                const dropdownId = link.dataset.bsTarget;
                const dropdownElement = document.querySelector(dropdownId);
                const bsCollapse = bsCollapses.find(c => c._element === dropdownElement);

                // Restore state
                if (localStorage.getItem('dropdown' + dropdownId) === 'true') {
                    bsCollapse.show();
                }

                // Update local storage on state change
                dropdownElement.addEventListener('shown.bs.collapse', () => {
                    localStorage.setItem('dropdown' + dropdownId, 'true');
                });

                dropdownElement.addEventListener('hidden.bs.collapse', () => {
                    localStorage.setItem('dropdown' + dropdownId, 'false');
                });
            });

            // Additional logic to handle non-collapse-triggering links
            allSidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (!link.dataset.bsToggle) {
                        // Close all dropdowns if the clicked link does not toggle any collapse
                        bsCollapses.forEach(c => {
                            if (c._element.classList.contains('show')) {
                                c.hide();
                            }
                        });
                    }
                });
            });
        });






        // This script only listens to clicks on the sidebar toggle and changes the sidebar's collapsed state accordingly.
        /*
                document.querySelector(".theme-toggle").addEventListener("click", () => {
                    toggleLocalStorage();
                    toggleRootClass();
                });

                function toggleRootClass() {
                    const current = document.documentElement.getAttribute('data-bs-theme');
                    const inverted = current == 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-bs-theme', inverted);
                }

                function toggleLocalStorage() {
                    if (isLight()) {
                        localStorage.removeItem("light");
                    } else {
                        localStorage.setItem("light", "set");
                    }
                }

                function isLight() {
                    return localStorage.getItem("light");
                }

                if (isLight()) {
                    toggleRootClass();
                }*/
    </script>
</body>
