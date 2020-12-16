@extends('layouts.master')

@section('title','新增比賽項目')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">新增比賽項目</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.item') }}">比賽項目</a></li>
                <li class="breadcrumb-item active" aria-current="page">新增比賽項目</li>
            </ol>
        </nav>
        <form action="{{ route('school_admins.item_update',$item->id) }}" method="post">
            @csrf
            @method('patch')
            <div class="form-row">
                <div class="form-group col-md-1">
                    <label for="order">排序</label>
                    <input type="text" class="form-control" id="order" name="order" placeholder="數字" value="{{ $item->order }}">
                </div>
                <div class="form-group col-md-5">
                    <label for="name">名稱<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="名稱" required value="{{ $item->name }}">
                </div>
                <div class="form-group col-md-3">
                    <?php
                    if($item->group==1){
                        $g1 = "selected";
                        $g2 = null;
                        $g3 = null;
                    }
                    if($item->group==2){
                        $g1 = null;
                        $g2 = "selected";
                        $g3 = null;
                    }
                    if($item->group==3){
                        $g1 = null;
                        $g2 = null;
                        $g3 = "selected";
                    }
                    ?>
                    <label for="sex">男女組别<span class="text-danger">*</span></label>
                    <select id="sex" class="form-control" name="group">
                        <option value="3" {{ $g3 }}>男子組+女子組</option>
                        <option value="1" {{ $g1 }}>男子組</option>
                        <option value="2" {{ $g2 }}>女子組</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <?php
                    if($item->type==1){
                        $t1 = "selected";
                        $t2 = null;
                        $t3 = null;
                    }
                    if($item->type==2){
                        $t1 = null;
                        $t2 = "selected";
                        $t3 = null;
                    }
                    if($item->type==3){
                        $t1 = null;
                        $t2 = null;
                        $t3 = "selected";
                    }
                    ?>
                    <label for="sex">類别<span class="text-danger">*</span></label>
                    <select id="sex" class="form-control" name="type">
                        <option value="1" {{ $t1 }}>徑賽</option>
                        <option value="2" {{ $t2 }}>田賽</option>
                        <option value="3" {{ $t3 }}>其他</option>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="limit" name="limit" @if($item->limit) checked @endif>
                    <label class="form-check-label" for="limit">限制選手參賽項目</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">儲存</button>
        </form>
    </div>
@endsection
