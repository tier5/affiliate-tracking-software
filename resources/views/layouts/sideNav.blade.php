<!--import sidebar nav -->
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
{{--             @if(auth()->user()->role == 'affiliate')
            <li>
            <a href="{{ route('dashboard') }}">
                <i class="fa fa-pie-chart"></i> <span>Dashboard</span>
            </a>
            </li>
            @endif
            @if(auth()->user()->role == 'admin')
 --}}                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-pie-chart"></i> <span>Dashboard</span>
                    </a>
                </li>

                {{-- <li>
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
                 </li>--}}
                <li>
                    <a href="{{ route('get.campaign') }}">
                        <i class="fa fa-star"></i>
                        <span>Campaign</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('all.affiliate') }}">
                        <i class="fa fa-user"></i>
                        <span>Affiliate</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('all.sales') }}">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Sales</span>
                    </a>
                </li>
                {{--<li>
                    <a href="{{ route('settings') }}">
                        <i class="fa fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>--}}
            {{-- @endif --}}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>