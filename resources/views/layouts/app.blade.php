<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom App CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Sortable JS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
</head>
<body>
<div id="app">
    <div class="container-fluid">
        <div class="row">
            @auth

            <!-- Sidebar Toggle Button (Visible on small screens) -->
            <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="bi bi-list"></i> Menu
            </button>

            <!-- Sidebar (Offcanvas for mobile) -->
            <div class="offcanvas offcanvas-start d-md-none text-dark sidebar" tabindex="-1" id="sidebarMenu"
                 aria-labelledby="sidebarMenuLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="sidebarMenuLabel">Project Manager</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                               href="{{ route('projects.index') }}" aria-label="Projects">
                                <i class="bi bi-folder"></i> Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                            <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- Sidebar (Static for large screens) -->
            <div class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <h4 class="sidebar-heading d-flex justify-content-center py-3 text-dark">Project Manager</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                               href="{{ route('projects.index') }}" aria-label="Projects">
                                <i class="bi bi-folder"></i> Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                            <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>

                </div>
            </div>
            @endauth

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>

    </div>
</div>

<!-- Bootstrap JavaScript Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.tiny.cloud/1/c8w85j313opcl9e5uo0227ykj1her0mvdcxz3cgfvfi36lrk/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce-editor', // Select your textarea
        plugins: 'lists link image table code',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
        menubar: false,
        height: 400,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save(); // Ensure content is synced to the textarea
            });
        }
    });

    // Add form submission handler to ensure the editor content is submitted
    document.getElementById('projectForm').addEventListener('submit', function (event) {
        tinymce.triggerSave(); // Trigger saving the content to the textarea
    });
</script>

</body>
</html>
