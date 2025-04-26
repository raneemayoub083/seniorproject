<nav class="navbar navbar-main navbar-expand-lg bg-transparent shadow-none position-absolute px-4 w-100 z-index-2">
    <div class="container-fluid py-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 ps-2 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="text-white opacity-5" href="javascript:;">Pages</a>
                </li>
                <li class="breadcrumb-item text-sm text-white active text-capitalize" aria-current="page"> {{ str_replace('-', ' ', Route::currentRouteName()) }}</li>
            </ol>
            <h6 class="text-white font-weight-bolder ms-2 text-capitalize"> {{ str_replace('-', ' ', Route::currentRouteName()) }}</h6>
        </nav>
        <div class="collapse navbar-collapse me-md-0 me-sm-4 mt-sm-0 mt-2" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <!-- <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" placeholder="Type here...">
                    </div> -->
            </div>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-flex align-items-center">

                    <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">

                        <livewire:auth.logout />
                    </a>
                </li>
                <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                            </div>
                        </a>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0 position-relative" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">
                            0
                        </span>
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
    function loadNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notificationsList');
                const count = document.getElementById('notificationCount');
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

                count.innerText = data.length;
                count.style.display = 'inline-block';

                data.forEach(notification => {
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
                                <p class="text-xs text-secondary mb-0">
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
        const diff = Math.floor((now - time) / 60000); // difference in minutes

        if (diff < 1) return 'Just now';
        if (diff < 60) return `${diff} min ago`;
        if (diff < 1440) return `${Math.floor(diff / 60)} hr ago`;
        return `${Math.floor(diff / 1440)} days ago`;
    }

    // Load notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    loadNotifications();
</script>