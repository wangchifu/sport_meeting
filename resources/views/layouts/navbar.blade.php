<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('index') }}">{{ config('app.name', 'Laravel') }}</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">

    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown">
            @guest
                <a class="nav-link" href="{{ route('login') }}">登入 <span class="sr-only">登入</span></a>
            @endguest
            @auth
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i> {{ auth()->user()->name }}</a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    @if(auth()->user()->admin)
                        <a class="dropdown-item" href="{{ route('users') }}">全站帳號</a>
                        <div class="dropdown-divider"></div>
                    @endif
                    @impersonating
                        <a class="dropdown-item" href="{{ route('school_admins.impersonate_leave') }}" data-toggle="modal" data-target="#impersonate_leaveModal">結束模擬</a>
                        <div class="dropdown-divider"></div>
                    @endImpersonating
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">登出</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endauth
        </li>
    </ul>

</nav>

<!-- Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">登出確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                你確定要登出此網站？
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('logout-form').submit()">確定登出</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="impersonate_leaveModal" tabindex="-1" role="dialog" aria-labelledby="impersonate_leaveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="impersonate_leaveModalLabel">結束模擬確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                你確定要結束模擬，返回原先帳號登入？
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                <a href="{{ route('school_admins.impersonate_leave') }}" class="btn btn-primary">確定結束</a>
            </div>
        </div>
    </div>
</div>
