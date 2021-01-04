<div class="sb-sidenav-menu">
    <div class="nav">
        <a class="nav-link" href="{{ route('all') }}">
            <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
            全站資料
        </a>
        @auth
            @php
                $school_admin = \App\SchoolAdmin::where('code',auth()->user()->code)->where('user_id',auth()->user()->id)->first();
            @endphp
            @if(!empty($school_admin))
                @if($school_admin->type === 1)
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-school"></i></div>
                        學校管理員
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('school_admins.api') }}">匯入學校 API</a>
                            <a class="nav-link" href="{{ route('school_admins.account') }}">帳號管理</a>
                            <a class="nav-link" href="{{ route('school_admins.action') }}">報名任務</a>
                            <a class="nav-link" href="{{ route('school_admins.item') }}">比賽項目</a>
                        </nav>
                    </div>
                @endif
            @endif
            <a class="nav-link" href="{{ route('show') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-star"></i></div>
                歷次成績
            </a>
            <?php
            $check1 = \App\StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', auth()->user()->id)->first();
            $check2 = \App\StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', auth()->user()->id . ',%')->first();
            $check3 = \App\StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', '%,' . auth()->user()->id)->first();

            $student_class = [];
            if(!empty($check1)) $student_class = $check1;
            if(!empty($check2)) $student_class = $check2;
            if(!empty($check3)) $student_class = $check3;
            ?>
            @if(!empty($student_class))
                <a class="nav-link" href="{{ route('class_teachers.sign_up') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-copy"></i></div>
                    導師報名
                </a>
            @endif
            @if(!empty($school_admin))
                @if($school_admin->type === 1)
                    <a class="nav-link" href="{{ route('class_teachers.sign_up') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-copy"></i></div>
                        導師報名
                    </a>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-download"></i></div>
                        各式表單
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('school_admins.students') }}">註冊選手名單</a>
                            <a class="nav-link" href="{{ route('school_admins.records') }}">項目記錄表</a>
                            <a class="nav-link" href="{{ route('school_admins.scores') }}">成績記錄單</a>
                        </nav>
                    </div>
                @endif
                @if($school_admin->type === 1 or $school_admin->type === 2)
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                        成績處理
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('school_scores.score_input') }}">成績登錄</a>
                            <a class="nav-link" href="{{ route('school_scores.score_print') }}">獎狀列印</a>
                        </nav>
                    </div>
                @endif
            @endif
        @endauth
    </div>
</div>
