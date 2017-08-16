<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <img class="logo-mini" src="{{ asset('images/Logo_short.png') }}" height="50" width="50">
        <!-- logo for regular state and mobile devices -->
        <img class="logo-lg" src="{{ asset('images/Logo.png') }}" height="50" width="130">
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        @if (Auth::user()->role != "affiliate")
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        @endif
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>
            <ul style="margin-right: 8px;" class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('logout') }}">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>