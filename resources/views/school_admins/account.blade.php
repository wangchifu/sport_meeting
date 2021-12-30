@extends('layouts.master')

@section('title','學校 帳號管理')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">帳號管理</h1>
        <div class="row">
            <?php
                $action_at = ($action=="at")?"active":null;
                $action_not_at = ($action=="not_at")?"active":null;
            ?>
            <div class="col-xl-12 col-md-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ $action_at }}" href="{{ route('school_admins.account') }}">在職</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $action_not_at }}"href="{{ route('school_admins.account_not') }}">停用</a>
                    </li>
                </ul>
                <br>
                <table class="table table-hover">
                    <thead class="table-primary">
                    <tr>
                        <th>
                            類別
                        </th>
                        <th>
                            職稱
                        </th>
                        <th>
                            姓名
                        </th>
                        <th>
                            動作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                {{ $user->kind }}
                            </td>
                            <td>
                                ({{ $user->semester }})
                                {{ $user->title }}
                            </td>
                            <td>
                                @if($user->disable)
                                    <span class="text-danger">[停用]</span>
                                @endif
                                <?php
                                $check1 = \App\StudentClass::where('semester',$user->semester)->where('code',$user->code)->where('user_ids',$user->id)->first();
                                $check2 = \App\StudentClass::where('semester',$user->semester)->where('code',$user->code)->where('user_ids','like',$user->id.',%')->first();
                                $check3 = \App\StudentClass::where('semester',$user->semester)->where('code',$user->code)->where('user_ids','like','%,'.$user->id)->first();
                                $check4 = \App\StudentClass::where('semester', $user->semester)->where('code', $user->code)->where('user_names', 'like','%' .$user->name. '%')->first();
                                ?>
                                @if(!empty($check1))
                                    @if(strlen($check1->user_ids) == strlen($user->id))
                                        {{ $check1->student_year }}年{{ $check1->student_class }}班
                                    @endif
                                @endif
                                @if(!empty($check2))
                                    {{ $check2->student_year }}年{{ $check2->student_class }}班
                                @endif
                                @if(!empty($check3))
                                    {{ $check3->student_year }}年{{ $check3->student_class }}班
                                @endif
                                @if(!empty($check4))
                                    {{ $check4->student_year }}年{{ $check4->student_class }}班
                                @endif

                                <?php
                                    $school_admin = \App\SchoolAdmin::where('code',$user->code)->where('user_id',$user->id)->first();
                                ?>
                                {{ $user->name }}
                                @if(!empty($school_admin))
                                    @if($school_admin->type === 1)
                                        <span class="text-primary">[管理權]</span>
                                    @endif
                                    @if($school_admin->type === 2)
                                        <span class="text-success">[成績權]</span>
                                    @endif
                                    @if($user->id != auth()->user()->id)
                                        <i class="fas fa-times-circle text-danger" data-toggle="modal" data-target="#accModal" data-whatever="{{ route('school_admins.account_remove_power',$user->id) }}" data-name="{{ $user->name }}" data-act="remove_power"></i></span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if(empty($school_admin))
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#accModal" data-whatever="{{ route('school_admins.account_set1',$user->id) }}" data-name="{{ $user->name }}" data-act="set1">設管理權</button>
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#accModal" data-whatever="{{ route('school_admins.account_set2',$user->id) }}" data-name="{{ $user->name }}" data-act="set2">設成績權</button>
                                @endif
                                @if($user->id != auth()->user()->id)
                                    @if($user->disable)
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#accModal" data-whatever="{{ route('school_admins.account_enable',$user->id) }}" data-name="{{ $user->name }}" data-act="enable">啟用</button>
                                    @else
                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#accModal" data-whatever="{{ route('school_admins.account_disable',$user->id) }}" data-name="{{ $user->name }}" data-act="disable">停用</button>
                                    @endif
                                    <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#accModal" data-whatever="{{ route('school_admins.impersonate',$user->id) }}" data-name="{{ $user->name }}" data-act="impersonate">模擬</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="accModal" tabindex="-1" role="dialog" aria-labelledby="accModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accModalLabel">請確認</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="showText"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                    <a href="" id="do" class="btn btn-primary">確定</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () { $('#accModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever')
            var act = button.data('act')
            var name = button.data('name')
            $('#do').attr("href", recipient);
            if(act == "set1"){
                $('#showText').text('給 ['+name+'] 管理權？');
            }
            if(act == "set2"){
                $('#showText').text('給 ['+name+'] 成績權？');
            }
            if(act == "disable"){
                $('#showText').text('將 ['+name+'] 的帳號停用？');
            }
            if(act == "enable"){
                $('#showText').text('將 ['+name+'] 的帳號重新啟用？');
            }
            if(act == "impersonate"){
                $('#showText').text('模擬 ['+name+'] 的帳號登入？');
            }
            if(act == "remove_power"){
                $('#showText').text('將 ['+name+'] 的權限刪除？');
            }

        })
        });

    </script>
@endsection
