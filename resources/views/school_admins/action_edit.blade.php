@extends('layouts.master')

@section('title','新增報名任務')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">修改報名任務</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.action') }}">報名設定</a></li>
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
                <?php
                $f1 = null;
                $f2 = null;
                $f3 = null;
                $f4 = null;
                if($action->frequency == 1 ) $f1 = "selected";
                if($action->frequency == 2 ) $f2 = "selected";
                if($action->frequency == 3 ) $f3 = "selected";
                if($action->frequency == 4 ) $f4 = "selected";
                ?>
                <div class="form-group col-md-2">
                    <label for="frequency">每人限報名項目<span class="text-danger">*</span></label>
                    <select id="frequency" class="form-control" name="frequency">
                        <option value="1" {{ $f1 }}>1</option>
                        <option value="2" {{ $f2 }}>2</option>
                        <option value="3" {{ $f3 }}>3</option>
                        <option value="4" {{ $f4 }}>4</option>
                    </select>
                </div>
                <?php
                $n4 = null;
                $n5 = null;
                if($action->numbers == 4 ) $n4 = "selected";
                if($action->numbers == 5 ) $n5 = "selected";
                ?>
                <div class="form-group col-md-2">
                    <label for="numbers">號碼布幾位數<span class="text-danger">*</span></label>
                    <select id="numbers" class="form-control" name="numbers">
                        <option value="4" {{ $n4 }}>4</option>
                        <option value="5" {{ $n5 }}>5</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">儲存</button>
        </form>
    </div>
@endsection
