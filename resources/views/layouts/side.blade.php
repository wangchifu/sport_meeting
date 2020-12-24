<div class="sb-sidenav-menu">
    <div class="nav">
        <div class="sb-sidenav-menu-heading">各校運動會</div>
        <a class="nav-link" href="">
            <div class="sb-nav-link-icon"><i class="fas fa-user-friends"></i></div>
            列出各校
        </a>
        @auth
            @php
                $school_admin = \App\SchoolAdmin::where('code',auth()->user()->code)->where('user_id',auth()->user()->id)->first();
            @endphp
            @if(!empty($school_admin))
                @if($school_admin->type === 1)
                    <div class="sb-sidenav-menu-heading">各校管理</div>
                    <a class="nav-link" href="{{ route('school_admins.api') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-school"></i></div>
                        匯入學校 API
                    </a>
                    <a class="nav-link" href="{{ route('school_admins.account') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        帳號管理
                    </a>
                    <a class="nav-link" href="{{ route('school_admins.action') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        報名任務
                    </a>
                    <a class="nav-link" href="{{ route('school_admins.item') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-running"></i></div>
                        比賽項目
                    </a>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        TTTTTT
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="#">設定報名</a>
                            <a class="nav-link" href="#">報名狀況</a>
                        </nav>
                    </div>
                @endif
            @endif
            <div class="sb-sidenav-menu-heading">各班導師</div>
            <a class="nav-link" href="{{ route('class_teachers.sign_up') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                報名比賽
            </a>
            @if(!empty($school_admin))
                @if($school_admin->type === 1 or $school_admin->type === 2)
                    <div class="sb-sidenav-menu-heading">成績管理</div>
                    <a class="nav-link" href="{{ route('school_admins.item') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        成績登入
                    </a>
                    <a class="nav-link" href="{{ route('school_admins.item') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        獎狀列印
                    </a>
                @endif
            @endif
        @endauth
    </div>
</div>
