<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
  data-theme="theme-default" data-assets-path="{{ asset('admin/assets/') }}"
  data-template="vertical-menu-template-no-customizer-starter" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Mobiplay - Dashboard</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.svg') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/tabler-icons.css') }}" />
  <!-- <link rel="stylesheet" href="../../../../assets/vendor/fonts/fontawesome.css" /> -->
  <!-- <link rel="stylesheet" href="../../../../assets/vendor/fonts/flag-icons.css" /> -->

  <!-- Core CSS -->

  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/rtl/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/rtl/theme-default.css') }}" />

  <link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/node-waves/node-waves.css') }}" />

  <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  <!-- Page CSS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Helpers -->
  <script src="{{ asset('admin/assets/vendor/js/helpers.js') }}"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="{{ asset('admin/assets/js/config.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('admin/assets/css/stylesheet.css') }}" />
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
              <img src="{{ asset('assets/images/logo.svg') }}" style="width: 100%; height : 100%; ;" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Mobiplay</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Page -->
          <li class="menu-item active">
            <a href="index.html" class="menu-link">
              <i class="menu-icon tf-icons ti ti-smart-home"></i>
              <div data-i18n="Dashboard">Dashboard</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="Users.html" class="menu-link">
              <i class="menu-icon tf-icons ti ti-user"></i>
              <div data-i18n="Users">Users</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="#" class="menu-link">
              <i class="menu-icon tf-icons ti ti-car"></i>
              <div data-i18n="Manage Tips">Manage Drivers</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="plans.html" class="menu-link">
              <i class="menu-icon tf-icons ti ti-calendar"></i>
              <div data-i18n="Manage Plans">Manage Plans</div>
            </a>
          </li>
          
          <li class="menu-item">
            <a href="admin.html" class="menu-link ">
              <i class="menu-icon tf-icons ti ti-user-plus"></i>
              <div data-i18n="Manage Admins">Manage Admins</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="workouts.html" class="menu-link ">
              <i class="menu-icon tf-icons ti ti-calendar"></i>
              <div data-i18n="Manage Admins">Manage Campains</div>
            </a>
          </li>
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="ti ti-menu-2 ti-md"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                  <i class="ti ti-search ti-md me-2 me-lg-4 ti-lg"></i>
                  <span class="d-none d-md-inline-block text-muted fw-normal">Search (Ctrl+/)</span>
                </a>
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Quick links  -->
              <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill btn-icon dropdown-toggle hide-arrow"
                  href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                  aria-expanded="false">
                  <i class="ti ti-layout-grid-add ti-md"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0">
                  <div class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                      <h6 class="mb-0 me-auto">Shortcuts</h6>
                      
                    </div>
                  </div>
                  <div class="dropdown-shortcuts-list scrollable-container">
                    <div class="row row-bordered overflow-visible g-0">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-calendar ti-26px text-heading"></i>
                        </span>
                        <a href="app-calendar.html" class="stretched-link">Test</a>
                        <small>Test</small>
                      </div>
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-file-dollar ti-26px text-heading"></i>
                        </span>
                        <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                        <small>Test Title</small>
                      </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-calendar ti-26px text-heading"></i>
                        </span>
                        <a href="app-calendar.html" class="stretched-link">Test</a>
                        <small>Test</small>
                      </div>
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-file-dollar ti-26px text-heading"></i>
                        </span>
                        <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                        <small>Test Title</small>
                      </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-calendar ti-26px text-heading"></i>
                        </span>
                        <a href="app-calendar.html" class="stretched-link">Test</a>
                        <small>Test</small>
                      </div>
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-file-dollar ti-26px text-heading"></i>
                        </span>
                        <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                        <small>Test Title</small>
                      </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-calendar ti-26px text-heading"></i>
                        </span>
                        <a href="app-calendar.html" class="stretched-link">Test</a>
                        <small>Test</small>
                      </div>
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="ti ti-file-dollar ti-26px text-heading"></i>
                        </span>
                        <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                        <small>Test Title</small>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- Quick links -->

              <!-- Notification -->
              <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                  href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                  aria-expanded="false">
                  <span class="position-relative">
                    <i class="ti ti-bell ti-md"></i>
                    <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-0">
                  <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                      <h6 class="mb-0 me-auto">Notification</h6>
                      <div class="d-flex align-items-center h6 mb-0">
                        <span class="badge bg-label-primary me-2">8 New</span>
                        <a href="javascript:void(0)"
                          class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
                          data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i
                            class="ti ti-mail-opened text-heading"></i></a>
                      </div>
                    </div>
                  </li>
                  <li class="dropdown-notifications-list scrollable-container">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item list-group-item-action dropdown-notifications-item">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <img src="{{ asset('admin/assets/images/avatars/1.jpg') }}" alt class="rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="small mb-1">Employee Notification</h6>
                            <small class="mb-1 d-block text-body">This is a test notification</small>
                            <small class="text-muted">1h ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                class="badge badge-dot"></span></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                class="ti ti-x"></span></a>
                          </div>
                        </div>
                      </li>

                    </ul>
                  </li>
                  <li class="border-top">
                    <div class="d-grid p-4">
                      <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                        <small class="align-middle">View all notifications</small>
                      </a>
                    </div>
                  </li>
                </ul>
              </li>
              <!--/ Notification -->

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('admin/assets/images/avatars/1.jpg') }}" alt class="rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item mt-0" href="pages-account-settings-account.html">
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-2">
                          <div class="avatar avatar-online">
                            <img src="../../assets/img/avatars/1.png" alt class="rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-0">John Doe</h6>
                          <small class="text-muted">Admin</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="pages-profile-user.html">
                      <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="pages-account-settings-account.html">
                      <i class="ti ti-settings me-3 ti-md"></i><span class="align-middle">Settings</span>
                    </a>
                  </li>

                  <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                  </li>

                  <div class="d-grid px-2 pt-2 pb-1">
                    <form method="POST" action="{{ route('admin.logout') }}">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-danger d-flex w-100" style="border: none;">
                        <small class="align-middle">Logout</small>
                        <i class="ti ti-logout ms-2 ti-14px"></i>
                      </button>
                    </form>
                  </div>
              </li>
            </ul>
            </li>
            <!--/ User -->
            </ul>
          </div>

          <!-- Search Small Screens -->
          <div class="navbar-search-wrapper search-input-wrapper d-none">
            <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
              aria-label="Search..." />
            <i class="ti ti-x search-toggler cursor-pointer"></i>
          </div>
        </nav>

        <!-- / Navbar -->

        <div class="content-wrapper container-xxl flex-grow-1">
          <div class=" container-p-y">
            <div class="row">
              <div class="col-sm-6 col-xl">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="content-left">
                        <h5 class="mb-1">100</h5>
                        <small>Active Advertisers</small>
                      </div>
                      <span class="badge bg-label-primary rounded-circle p-2">
                        <i class="ti ti-user ti-lg"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xl">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="content-left">
                        <h5 class="mb-1">150</h5>
                        <small>Active Drivers</small>
                      </div>
                      <span class="badge bg-label-success rounded-circle p-2">
                        <i class="ti ti-car"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xl">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="content-left">
                        <h5 class="mb-1">50</h5>
                        <small>Active campaigns</small>
                      </div>
                      <span class="badge bg-label-success rounded-circle p-2">
                        <i class="ti ti-calendar"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xl">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="content-left">
                        <h5 class="mb-1">$7000</h5>
                        <small>Total Revenue</small>
                      </div>
                      <span class="badge bg-label-success rounded-circle p-2">
                        <i class="ti ti-wallet"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card mt-5">
              <div class="d-flex align-items-end row">
                <div class="col-7">
                  <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0">Create new Driver</h5>
                    <p class="mb-2">Create a new Driver and fill in thier details</p>
                    <a href="adduser.html" class="btn btn-primary waves-effect waves-light">Create</a>
                  </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                  <div class="card-body pb-0 px-0 px-md-4">
                    <img src="{{ asset('assets/images/mobidriver.jpg') }}" height="140" alt="view sales">
                  </div>
                </div>
              </div>
            </div>
            <div class="card mt-5 align-items-center">
              <h5 class="mt-2">Active Plans</h5>
              <div class="d-flex align-items-end row">
                <canvas class="mb-2" id="chartjs-pie" style="height: 400px;"></canvas>
              </div>
            </div>
          </div>
        </div>
        <!-- / Content -->

        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
          <div class="container-xxl">
            <div
              class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
              <div class="text-body">
                ©
                <script>
                  document.write(new Date().getFullYear());
                </script>
                , all Rights Reserved <a href="#" target="_blank" class="footer-link">Mobiplay</a>
              </div>
              <div class="d-none d-lg-inline-block">
                <a href="#" target="_blank" class="footer-link me-4">Support</a>
              </div>
            </div>
          </div>
        </footer>
        <!-- / Footer -->

        <div class="content-backdrop fade"></div>
      </div>
      <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
  </div>

  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>

  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script>
    window.theme = {
      primary: "#007bff",   // Example color for primary
      success: "#28a745",   // Example color for success
      warning: "#ffc107"    // Example color for warning
    };
  </script>
  <script>
    new Chart(document.getElementById("chartjs-pie"), {
      type: "pie",
      data: {
        labels: ["Baisc Plan", "Priority Plan", "Advance Plan"],
        datasets: [{
          data: [260, 125, 54],
          backgroundColor: [
            window.theme.primary,
            window.theme.success,
            window.theme.warning,
          ],
          borderColor: "transparent"
        }]
      },
      options: {
        maintainAspectRatio: false,
        cutoutPercentage: 65,
      }
    });
  </script>
  
  <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/libs/hammer/hammer.js') }}"></script>

  <script src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="{{ asset('admin/assets/js/main.js') }}"></script>

  <!-- Page JS -->
</body>

</html>