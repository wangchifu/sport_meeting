@extends('layouts.master')

@section('title','學校 API')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">使用學校 API 匯入學生資料</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#create_api">新增 API</button>
                <a href="{{ route('school_admins.api_teach') }}" class="btn btn-info btn-sm">教學</a>
                <br><br>
                <a href="{{ route('school_admins.import') }}" class="btn btn-outline-success btn-sm">若無法從 API 拉下學生資料，則改用 Excel 匯入的方式</a>

                @if(!empty($school_api))
                    <table class="table">
                        <thead class="table-primary">
                            <tr>
                                <th style="width:40%">
                                    用戶端 ID
                                </th>
                                <th style="width:40%">
                                    用戶端密碼
                                </th>
                                <th style="width:20%">
                                    動作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ $school_api->client_id }}
                                </td>
                                <td>
                                    {{ $school_api->client_secret }}
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#api_pullModal">拉回資料</button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#api_destroyModal">刪除</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <form action="{{ route('school_admins.api_destroy',$school_api->id) }}" method="post" id="api_destroy">
                        @csrf
                        @method('delete')
                    </form>
		    @endif
		    @include('layouts.errors')
                @if(empty($class_data))
                    <div class="alert alert-danger" role="alert">
                        學生班級資料沒有進來，建議重拉 API，若再不行，可能是 cloudschool API 有問題，考慮用 Excel 匯入學生。
                    </div>
                @else
                    <hr>
                    <table class="table">
                        <thead class="table-warning">
                        <tr>
                            <th style="width:40%">
                                學期
                            </th>
                            <th style="width:40%">
                                班級數
                            </th>
                            <th style="width:20%">
                                學生數
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($student_data as $k=>$v)
                            <tr>
                                <td>
                                    {{ $k }}
                                </td>
                                <td>
                                    {{ $class_data[$k] }} <a href="{{ route('school_admins.student_class',$k) }}" class="btn btn-info btn-sm">詳細資料...</a>
                                </td>
                                <td>
                                    {{ $v }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            <!-- Modal -->
                <div class="modal fade" id="api_destroyModal" tabindex="-1" role="dialog" aria-labelledby="api_destroyModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="api_destroyModalLabel">刪除確認</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                你確定要刪除此 API？
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('api_destroy').submit()">確定刪除</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="api_pullModal" tabindex="-1" role="dialog" aria-labelledby="api_pullModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="api_pullModalLabel">拉下資料確認</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                你確定要拉下貴校學期資料？已存在資料者將更新，頻繁拉下資料將造成系統負擔。
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                                <button type="button" class="btn btn-primary" onclick="location.href='api_pull'">確定拉下</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="create_api" tabindex="-1" role="dialog" aria-labelledby="create_apiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="create_apiModalLabel">CloudSchool API 匯入</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('school_admins.api_store') }}" method="post" id="api_store">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-client_id" class="col-form-label">用戶端 ID:</label>
                            <input type="text" class="form-control" id="client_id-name" name="client_id" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="message-client_secret" class="col-form-label">用戶端密碼:</label>
                            <input type="text" class="form-control" id="client_secret-name" name="client_secret" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('api_store').submit()">送出</button>
                </div>
            </div>
        </div>
    </div>
@endsection
