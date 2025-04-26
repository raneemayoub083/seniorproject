<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/visionvoicelogo.png') }}" class="navbar-brand-img" alt="Vision Voice Logo">

        </a>
    </div>
    <hr class="horizontal dark mt-7">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if(Auth::check())
            @if(Auth::user()->role->name == 'Admin')
            <li class="nav-item pb-2">
                <a class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>shop </title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="shop-" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"
                                                id="Path" opacity="0.598981585"></path>
                                            <path class="color-background"
                                                d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'academic_year') ? 'active' : '' }}"
                    href="{{ route('academic_year.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>shop </title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-IconsA" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="shop-" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"
                                                id="Path" opacity="0.598981585"></path>
                                            <path class="color-background"
                                                d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Academic Years</span>
                </a>
            </li>

            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'grade') ? 'active' : '' }}"
                    href="{{ route('grade.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Grades</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'application') ? 'active' : '' }}"
                    href="{{ route('application.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Applications</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'teacher') ? 'active' : '' }}"
                    href="{{ route('teacher.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Teachers</span>
                </a>
            </li>
            @endif
            @endif
            @if(Auth::check())
            @if(Auth::user()->role->name == 'Teacher')
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'teacherdash.dashboard') ? 'active' : '' }}"
                    href="{{ route('teacherdash.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{
    Str::startsWith(Route::currentRouteName(), [
        'teacherdash.class',
        'teacher.exams',
        'lessons'
    ]) ? 'active' : '' }}"
                    href="{{ route('teacherdash.classes') }}">


                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Classes</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'attendance.calendar') ? 'active' : '' }}"
                    href="{{ route('attendance.calendar') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 7V3M16 7V3M3 11H21M5 5H19C20.1046 5 21 5.89543 21 7V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V7C3 5.89543 3.89543 5 5 5Z"
                                stroke="#344767" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Attendance</span>
                </a>
            </li>
            @endif
            @endif
            @if(Auth::check())
            @if(Auth::user()->role->name == 'Student')
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'studentdash.dashboard') ? 'active' : '' }}"
                    href="{{ route('studentdash.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), [
        'studentdash.classes','studentdash.viewGrades']) ? 'active' : '' }}"
                    href="{{ route('studentdash.classes') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Classes</span>
                </a>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'studentdash.activeclass') ||  Str::startsWith(Route::currentRouteName(), 'lessons') ? 'active' : '' }}"
                    href="{{ route('studentdash.activeclass') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>calendarr</title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="calendarr" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background"
                                                d="M40,4 L36,4 L36,0 L32,0 L32,4 L16,4 L16,0 L12,0 L12,4 L8,4 C3.6,4 0,7.6 0,12 L0,36 C0,40.4 3.6,44 8,44 L40,44 C44.4,44 48,40.4 48,36 L48,12 C48,7.6 44.4,4 40,4 Z M44,36 C44,38.2 42.2,40 40,40 L8,40 C5.8,40 4,38.2 4,36 L4,20 L44,20 L44,36 Z M8,12 L40,12 C42.2,12 44,13.8 44,16 L4,16 C4,13.8 5.8,12 8,12 Z"
                                                id="Path"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Active Class</span>
                </a>
            </li>
            @endif
            @endif


        </ul>
    </div>
</aside>