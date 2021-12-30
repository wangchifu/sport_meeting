@extends('layouts.master')

@section('title','學校 API')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">匯入學校 API</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <hr>
                設定參考：<a href="https://cloudschool.chc.edu.tw" target="_blank">cloudschool</a> > 系統管理  > 模組管理  >  API及憑證設定  >  +新增學校伺服器API
                <br>
                名稱：自取<br>
                類型：學校伺服器<br>
                限用伺服器IP：163.23.200.3<br>
                已授權的重新導向URL：https://sm.chc.edu.tw/callback<br>
                <img src="{{ asset('images/api.png') }}">
            </div>
        </div>
    </div>
@endsection
