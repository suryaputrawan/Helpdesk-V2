<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link arrow-none" href="{{ route('dashboard') }}" id="topnav-dashboard" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            {{-- <i class="mdi mdi-view-dashboard me-1"></i>  --}}
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link arrow-none" href="{{ route('ticket.index') }}" id="topnav-dashboard" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            {{-- <i class="mdi mdi-ticket-confirmation"></i>  --}}
                            Tickets
                        </a>
                    </li>

                    @if (auth()->user()->role == "Admin")
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{-- <i class="mdi mdi-card-bulleted-settings-outline me-1"></i>  --}}
                                Master Data 
                                <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                <a href="{{ route('office.index') }}" class="dropdown-item">Offices</a>
                                <a href="{{ route('department.index') }}" class="dropdown-item">Departments</a>
                                <a href="{{ route('category.index') }}" class="dropdown-item">Categories</a>
                                <a href="{{ route('status.index') }}" class="dropdown-item">Status</a>
                                <a href="{{ route('location.index') }}" class="dropdown-item">Locations</a>
                                <a href="{{ route('item.index') }}" class="dropdown-item">Items</a>
                                <a href="{{ route('user.index') }}" class="dropdown-item">Users</a>
                            </div>
                        </li>
                    @elseif (auth()->user()->role == "Technician")
                        <li class="nav-item dropdown">
                            <a class="nav-link arrow-none" href="{{ route('item.index') }}" id="topnav-dashboard" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                Items
                            </a>
                        </li>
                    @endif 

                    @if (auth()->user()->role == "User")
                        {{-- Tidak terdapat menu laporan --}}
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link arrow-none" href="{{ route('report.index') }}" id="topnav-dashboard" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                Report
                            </a>
                        </li>
                    @endif
                </ul> <!-- end navbar-->
            </div>
            <div class="float-end">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        @if (auth()->user()->role == 'Technician')
                            {{-- Tidak ada menu create ticket untuk teknisi --}}
                        @else
                            <a class="nav-link arrow-none" href="{{ route('ticket.create') }}" id="topnav-dashboard" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                Create Ticket
                            </a>
                        @endif                       
                    </li>
                </ul> <!-- end navbar-->
            </div> <!-- end .collapsed-->
        </nav>
    </div> <!-- end container-fluid -->
</div>