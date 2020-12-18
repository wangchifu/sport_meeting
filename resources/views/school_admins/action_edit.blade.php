@extends('layouts.master')

@section('title','新增報名任務')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">修改報名任務</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.item') }}">報名設定</a></li>
                <li class="breadcrumb-item active" aria-current="page">修改報名任務</li>
            </ol>
        </nav>
        <form action="{{ route('school_admins.action_update',$action->id) }}" method="post">
            @method('patch')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="name">名稱<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $action->name }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">儲存</button>
        </form>
    </div>
@endsection
