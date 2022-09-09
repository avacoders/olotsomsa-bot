
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset("dist/img/AdminLTELogo.png") }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset("dist/img/user2-160x160.jpg") }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item" >
                    <a href="{{ route('category.index') }}" class="nav-link {{ request()->is('admin/categories/*') || request()->is('admin/categories') ? 'active': '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Kategoriyalar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('product.index') }}" class="nav-link {{ request()->is('admin/products/*') || request()->is('admin/products') ? 'active': '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Maxsulotlar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch.index') }}" class=" nav-link {{ request()->is('admin/branches/*') || request()->is('admin/branches') ? 'active': '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Filiallar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class=" nav-link {{ request()->is('admin/useres/*') || request()->is('admin/users') ? 'active': '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Foydalanuvchilar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="nav-link " type="submit">Chiqish</button>
                    </form>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
