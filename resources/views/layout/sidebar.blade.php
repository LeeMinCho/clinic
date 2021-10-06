<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ url('assets') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ auth()->user()->fullname }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
   with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link @if (request()->is('dashboard*')) active @endif">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user') }}" class="nav-link @if (request()->is('user*')) active @endif">
                    <i class="nav-icon fas fa-users"></i>
                    <p>User</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paramedic') }}" class="nav-link @if (request()->is('paramedic*')) active @endif">
                    <i class="nav-icon fas fa-user-md"></i>
                    <p>Paramedic</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('menu') }}" class="nav-link @if (request()->is('menu*')) active @endif">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <p>Menu</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('screen') }}" class="nav-link @if (request()->is('screen*')) active @endif">
                    <i class="nav-icon fas fa-tv"></i>
                    <p>Screen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('clinic') }}" class="nav-link @if (request()->is('clinic*')) active @endif">
                    <i class="nav-icon fas fa-clinic-medical"></i>
                    <p>Clinic</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Sign Out</p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->