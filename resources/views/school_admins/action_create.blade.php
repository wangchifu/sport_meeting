@extends('layouts.master')

@section('title','新增報名任務')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">新增報名任務</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.action') }}">「報名任務」列表</a></li>
                <li class="breadcrumb-item active" aria-current="page">新增報名任務</li>
            </ol>
        </nav>
        <form action="{{ route('school_admins.action_add') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="semester">學期<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="semester" name="semester" value="{{ get_date_semester(date('Y-m-d')) }}" placeholder="4碼" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="name">名稱<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ get_date_semester(date('Y-m-d')) }} 學期校慶運動會報名" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="started_at">開始報<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="started_at" name="started_at" value="{{ date('Y-m-d') }}" placeholder="11碼" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="stopped_at">最後一天<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="stopped_at" name="stopped_at" value="{{ date('Y-m-d') }}" placeholder="11碼" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="numbers">號碼布幾位數<span class="text-danger">*</span></label>
                    <select id="numbers" class="form-control" name="numbers">
                        <option value="4" selected>4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="track">每人可報名徑賽最多幾項<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="track" name="track" value="2" maxlength="1" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="field">每人可報名田賽最多幾項<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="field" name="field" value="2" maxlength="1" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="frequency">每人報名項目合計最多幾項<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="frequency" name="frequency" value="2" maxlength="1" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="open" name="open" checked>
                    <label class="form-check-label" for="open">開放他校查詢(姓名會隱藏)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('確定？')">新增</button>
        </form>
    </div>
@endsection
