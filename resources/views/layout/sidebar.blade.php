<nav class="pcoded-navbar menu-light ">
    <div class="navbar-wrapper  ">
        <div class="navbar-content scroll-div ">

            <div class="">
                <div class="main-menu-header">
                    <img class="img-radius" src="{{ asset('storage/profile_pics/'.Auth()->User()->profile_pic) }}" alt="Profile">
                    <div class="user-details">
                        <div id="more-details">{{filter_company_id(Auth()->User()->roles->toArray()[0]['name']) ?? 'Guest'}} <i class="fa fa-caret-down"></i></div>
                    </div>

                </div>
                <div class="collapse" id="nav-user-link d-none">
                    <ul class="list-unstyled">
                        <li class="list-group-item"><a href="#!"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
                        <li class="list-group-item"><a href="#!"><i class="feather icon-settings m-r-5"></i>Settings</a></li>

                        <li class="list-group-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="feather icon-log-out m-r-5"></i>Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="nav pcoded-inner-navbar ">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigation</label>
                </li>

                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link "><span class="pcoded-micon"><i class="fa fa-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                </li>

                @can('view-roles')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-user-tag"></i></span><span class="pcoded-mtext">Roles</span></a>
                    <ul class="pcoded-submenu">
                        @can('create-roles')
                        <li><a href="{{ route('roles.create') }}">Add New</a></li>
                        @endcan
                        <li><a href="{{ route('roles.index') }}">List</a></li>
                    </ul>
                </li>
                @endcan

                @can('view-users')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-users"></i></span><span class="pcoded-mtext">Users</span></a>
                    <ul class="pcoded-submenu">
                        @can('create-users')
                        <li><a href="{{ route('users.create') }}">Add New</a></li>
                        @endcan
                        <li><a href="{{ route('users.list') }}">List</a></li>
                    </ul>
                </li>
                @endcan

                @can('view-companies')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-user-tag"></i></span><span class="pcoded-mtext">Companies</span></a>
                    <ul class="pcoded-submenu">
                        @can('create-companies')
                        <li><a href="{{ route('companies.create') }}">Add New</a></li>
                        @endcan
                        <li><a href="{{ route('companies.list') }}">List</a></li>
                    </ul>
                </li>
                @endcan

                @can('view-jd-tasks')
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-folder"></i></span><span class="pcoded-mtext">JD Tasks</span></a>
                        <ul class="pcoded-submenu">
                            @can('create-jd-tasks')
                                <li><a href="{{ route('jd.create') }}">Add New</a></li>
                            @endcan
                            <li><a href="{{ route('jd.list') }}">List</a></li>
                        </ul>
                    </li>
                   
                @endcan

                @can('view-departments')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-building"></i></span><span class="pcoded-mtext">Departments</span></a>
                    <ul class="pcoded-submenu">
                        @can('create-departments')
                        <li><a href="{{ route('departments.create') }}">Add New</a></li>
                        @endcan
                        <li><a href="{{ route('departments.list') }}">List</a></li>
                    </ul>
                </li>
                @endcan

                @can('view-projects')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-folder"></i></span><span class="pcoded-mtext">Projects</span></a>
                    <ul class="pcoded-submenu">
                        @can('create-projects')
                        <li><a href="{{ route('projects.create') }}">Add New</a></li>
                        @endcan
                        <li><a href="{{ route('projects.list') }}">List</a></li>
                    </ul>
                </li>
                @endcan

                @can('view-tasks')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-tasks"></i></span><span class="pcoded-mtext">Tasks</span></a>
                    <ul class="pcoded-submenu">
                        @can('create-tasks')
                        <li><a href="{{ route('tasks.create') }}">Add New</a></li>
                        @endcan
                        <li><a href="{{ route('tasks.list') }}">List</a></li>
                        <li><a href="{{ route('tasks.report') }}">Reports</a></li>
                    </ul>
                </li>
                @endcan

                <li class="nav-item pcoded-menu-caption ">
                    <label>Settings</label>
                </li>
                
                <li class="nav-item pcoded-hasmenu ">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-bell"></i></span><span class="pcoded-mtext">Notifications</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{ route('notifications.list') }}">All Notification</a></li>
                        <!-- <li><a href="#">Porile Setting</a></li> -->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>