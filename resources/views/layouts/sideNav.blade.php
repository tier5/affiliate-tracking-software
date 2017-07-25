<!--import sidebar nav -->
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li>
            <a href="{{ route('agency.dashboard') }}">
                <i class="fa fa-pie-chart"></i> <span>Dashboard</span>
            </a>
            </li>
            @if(auth()->user()->role == 'admin')
                <li>
                    <a href="{{ route('get.add.affiliate') }}">
                        <i class="fa fa-link"></i>
                        <span>Add Affiliate </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('allAffiliate') }}">
                        <i class="fa fa-user"></i>
                        <span>Show Affiliate</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings') }}">
                        <i class="fa fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>