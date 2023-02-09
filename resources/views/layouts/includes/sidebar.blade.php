<div id="sidebar-menu">

    <ul id="side-menu">

        <li class="menu-title">Navigation</li>

        <li>
            <a href="{{ route('dashboard') }}">
                <i class="mdi mdi-view-dashboard-outline"></i>
                {{-- <span class="badge bg-success rounded-pill float-end">9+</span> --}}
                <span> Dashboard </span>
            </a>
        </li>

        <li>
            <a href="{{ route('ticket.index') }}">
                <i class="mdi mdi-briefcase-variant-outline"></i>
                 <span> Tickets </span>
            </a>    
        </li>

        <li class="menu-title mt-2">Utilities</li>

        <li>
            <a href="#sidebarAuth" data-bs-toggle="collapse">
                <i class="mdi mdi-account-multiple-plus-outline"></i>
                <span> Master Data </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarAuth">
                <ul class="nav-second-level">
                    <li>
                        <a href="{{ route('office') }}">Offices</a>
                    </li>
                    <li>
                        <a href="{{ route('department.index') }}">Department</a>
                    </li>
                    <li>
                        <a href="{{ route('status.index') }}">Status</a>
                    </li>
                    <li>
                        <a href="{{ route('category.index') }}">Categories</a>
                    </li>
                    <li>
                        <a href="{{ route('user.index') }}">Users</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>

</div>