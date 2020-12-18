@extends('layouts.master')

@section('title','新增報名任務')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">新增報名任務</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.item') }}">報名設定</a></li>
                <li class="breadcrumb-item active" aria-current="page">新增報名任務</li>
            </ol>
        </nav>
        <form action="{{ route('school_admins.action_add') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="name">名稱<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->semester }} 學期校慶運動會報名" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="frequency">每人限報名項目<span class="text-danger">*</span></label>
                    <select id="frequency" class="form-control" name="frequency">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="numbers">號碼布幾位數<span class="text-danger">*</span></label>
                    <select id="numbers" class="form-control" name="numbers">
                        <option value="4" selected>4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">新增</button>
        </form>
    </div>
@endsection
