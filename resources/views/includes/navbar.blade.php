<div class="container-fluid g-0">
    <div class="row">
        <div class="col-lg-12 p-0">
            <div class="header_iner d-flex justify-content-between align-items-center">
                <div class="sidebar_icon d-lg-none">
                    <i class="ti-menu"></i>
                </div>
                <div class="header_right d-flex justify-content-between align-items-center" style="margin-left: 97%">
                    <div class="profile_info">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                            style="margin-top: -20px; margin-bottom: -20px" height="50" class="rounded-circle" />
                        <div class="profile_info_iner">
                            <div class="profile_author_name">
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                            <div class="profile_info_details">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" href="#" type="submit">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>