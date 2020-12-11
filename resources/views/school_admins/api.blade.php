@extends('layouts.master')

@section('title','學校 API')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">匯入學校 API</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                @if(empty($school_api))
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#create_api">新增 API</button>
                    <hr>
                    設定參考：
                    <br>
                    <img src="{{ asset('images/api.png') }}">
                @else
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
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#api_destroyModal">刪除</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <form action="{{ route('school_admins.api_destroy',$school_api->id) }}" method="post" id="api_destroy">
                        @csrf
                        @method('delete')
                    </form>

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
                @endif
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
