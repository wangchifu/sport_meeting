@extends('layouts.master')

@section('title','學校 帳號管理')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">帳號管理</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <table class="table">
                    <thead class="table-primary">
                    <tr>
                        <th>
                            類別
                        </th>
                        <th>
                            職稱
                        </th>
                        <th>
                            導師班
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
                                {{ $user->title }}
                            </td>
                            <td>

                            </td>
                            <td>
                                <?php
                                    $school_admin = \App\SchoolAdmin::where('code',$user->code)->where('user_id',$user->id)->where('type','1')->first();
                                ?>
                                @if(!empty($school_admin))
                                    @if($school_admin->type === 1)
                                        <i class="fas fa-crown text-warning"></i>
                                    @endif
                                @endif
                                {{ $user->name }}
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm">修改</button>
                                @if($user->id != auth()->user()->id)
                                    <button class="btn btn-danger btn-sm">停用</button>
                                    <a href="{{ route('school_admins.impersonate',$user->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定模擬？')">模擬</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
@endsection
