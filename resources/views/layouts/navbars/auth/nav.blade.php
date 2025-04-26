<main class="main-content mt-1 border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
        navbar-scroll="true" style="background-color: #3674B5;">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">

            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
                <div class="nav-item d-flex align-self-end">
                    <!-- <a href="https://www.creative-tim.com/product/soft-ui-dashboard-laravel-livewire" target="_blank"
                        class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                        Download
                    </a> -->
                </div>
                <div class="ms-md-3 pe-md-3 d-flex align-items-center">
                    <!-- <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" placeholder="Type here...">
                    </div> -->
                </div>
                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item d-flex align-items-center ">
                        <a href="javascript:;" class="nav-link text-white font-weight-bold px-3 d-flex align-items-center btn btn-danger btn-sm rounded-pill shadow-lg" style="background-color: #ffffff20;">

                            <livewire:auth.logout />
                        </a>
                    </li>

                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner d-flex flex-column justify-content-center align-items-center" style="width: 35px; height: 35px; background-color: #ffffff20; border-radius: 50%; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                                <span style="display: block; width: 20px; height: 2px; background: white; margin: 3px 0; border-radius: 4px;"></span>
                                <span style="display: block; width: 20px; height: 2px; background: white; margin: 3px 0; border-radius: 4px;"></span>
                                <span style="display: block; width: 20px; height: 2px; background: white; margin: 3px 0; border-radius: 4px;"></span>
                            </div>
                        </a>
                    </li>


                    <li class="nav-item dropdown pe-2 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="position-relative d-inline-block">
                                <i class="fa fa-bell cursor-pointer" style="font-size:30px"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount" style="font-size: 10px;">
                                    0
                                </span>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 ms-n4" aria-labelledby="dropdownMenuButton" id="notificationsList">
                            <!-- Notifications will be dynamically inserted here -->
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        // Fix 1: Use 'let' instead of 'const' so you can change it
        let currentUserRole = '{{ auth()->user()->role->name }}'; // Extract the role name (Student, Teacher, Parent)
// console.log('Current User Role:', currentUserRole);
        // Fix 2: Normalize the role
        if (currentUserRole === 'Student') {
            currentUserRole = 'students';
        } else if (currentUserRole === 'Teacher') {
            currentUserRole = 'teachers';
        } else if (currentUserRole === 'Admin') {
            currentUserRole = 'admins';
        } else if (currentUserRole === 'Parent') {
            currentUserRole = 'parents';
        }
// console.log('Normalized User Role:', currentUserRole);
        function loadNotifications() {
            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notificationsList');
                    const count = document.getElementById('notificationCount');
                    // console.log('Notifications Data:', data);
                    list.innerHTML = '';

                    if (data.length === 0) {
                        list.innerHTML = `
                        <li class="text-center">
                            <span class="text-sm text-secondary">No new notifications</span>
                        </li>
                    `;
                        count.style.display = 'none';
                        return;
                    }

                    // ✅ Filter notifications based on currentUserRole
                    const filteredNotifications = data.filter(notification => {
                        try {
                            const audience = JSON.parse(notification.audience);
                            return audience.includes(currentUserRole);
                        } catch (e) {
                            console.error('Invalid audience data:', notification.audience);
                            return false;
                        }
                    });

                    if (filteredNotifications.length === 0) {
                        list.innerHTML = `
                        <li class="text-center">
                            <span class="text-sm text-secondary">No new notifications for you</span>
                        </li>
                    `;
                        count.style.display = 'none';
                        return;
                    }

                    count.innerText = filteredNotifications.length;
                    count.style.display = 'inline-block';

                    filteredNotifications.forEach(notification => {
                        const item = document.createElement('li');
                        item.className = 'mb-2';

                        item.innerHTML = `
                        <a class="dropdown-item border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                                <div class="my-auto">
                                    <div class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
                                        <i class="fa fa-bell text-white"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="text-sm font-weight-normal mb-1">
                                        <span class="font-weight-bold">${notification.title}</span>
                                    </h6>
                                    <p class="text-xs text-secondary mb-1">
                                        ${notification.message ? notification.message : 'No description available.'}
                                    </p>
                                    <p class="text-xxs text-muted mb-0">
                                        <i class="fa fa-clock me-1"></i>
                                        ${formatTimeAgo(notification.created_at)}
                                    </p>
                                </div>
                            </div>
                        </a>
                    `;
                        list.appendChild(item);
                    });
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        // Helper function: format time ago
        function formatTimeAgo(timestamp) {
            const time = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - time) / 60000);

            if (diff < 1) return 'Just now';
            if (diff < 60) return `${diff} min ago`;
            if (diff < 1440) return `${Math.floor(diff / 60)} hr ago`;
            return `${Math.floor(diff / 1440)} days ago`;
        }

        // Load notifications every 30 seconds
        setInterval(loadNotifications, 30000);
        loadNotifications();
    </script>