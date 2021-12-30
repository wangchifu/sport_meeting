@extends('layouts.master')

@section('title','新增報名任務')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">修改報名任務</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.action') }}">「報名任務」列表</a></li>
                <li class="breadcrumb-item active" aria-current="page">修改報名任務</li>
            </ol>
        </nav>
        <form action="{{ route('school_admins.action_update',$action->id) }}" method="post">
            @method('patch')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="semester">學期<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="semester" name="semester" value="{{ $action->semester }}" placeholder="4碼" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="name">名稱<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $action->name }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="started_at">開始報<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="started_at" name="started_at" value="{{ $action->started_at }}" placeholder="11碼" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="stopped_at">最後一天<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="stopped_at" name="stopped_at" value="{{ $action->stopped_at }}" placeholder="11碼" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <?php
                    $s1 = ($action->numbers==4)?"selected":null;
                    $s2 = ($action->numbers==5)?"selected":null;
                    ?>
                    <label for="numbers">號碼布幾位數<span class="text-danger">*</span></label>
                    <select id="numbers" class="form-control" name="numbers">
                        <option value="4" {{ $s1 }}>4</option>
                        <option value="5" {{ $s2 }}>5</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="track">每人可報名徑賽最多幾項<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="track" name="track" value="{{ $action->track }}" maxlength="1" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="field">每人可報名田賽最多幾項<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="field" name="field" value="{{ $action->field }}" maxlength="1" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="frequency">每人報名項目合計最多幾項<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="frequency" name="frequency" value="{{ $action->frequency }}" maxlength="1" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="open" name="open" @if($action->open) checked @endif>
                    <label class="form-check-label" for="open">開放他校查詢(姓名會隱藏)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('確定？')">儲存</button>
        </form>
    </div>
@endsection
