<nav class="sidebar vertical-scroll  ps-container ps-theme-default ps-active-y">
    <div class="logo d-flex justify-content-between">
        <a href="index-2.html"><img src="{{ asset('pages/img/logo.png') }}" alt=""></a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    <ul id="sidebar_menu">
        <li class="">
            <a href="{{ route('dashboard_index') }}" aria-expanded="false">
                <div class="icon_menu">
                    <img src="{{ asset('pages/img/menu-icon/dashboard.svg') }}" alt="">
                </div>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('index_crew') }}" aria-expanded="false">
                <div class="icon_menu">
                    <img src="{{ asset('pages/img/menu-icon/16.svg') }}" alt="">
                </div>
                <span>Crew</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('index_absen') }}" aria-expanded="false">
                <div class="icon_menu">
                    <img src="{{ asset('pages/img/menu-icon/11.svg') }}" alt="">
                </div>
                <span>Absen</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('index_pengumuman') }}" aria-expanded="false">
                <div class="icon_menu">
                    <img src="{{ asset('pages/img/menu-icon/4.svg') }}" alt="">
                </div>
                <span>Pengumuman</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('index_laporan') }}" aria-expanded="false">
                <div class="icon_menu">
                    <img src="{{ asset('pages/img/menu-icon/8.svg') }}" alt="">
                </div>
                <span>Laporan Harian</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('index_setting') }}" aria-expanded="false">
                <div class="icon_menu">
                    <img src="{{ asset('pages/img/menu-icon/7.svg') }}" alt="">
                </div>
                <span>Jam Dan Lokasi</span>
            </a>
        </li>
    </ul>
</nav>