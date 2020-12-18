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
        <form action="{{ route('school_admins.item_add') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-1">
                    <label for="order">排序</label>
                    <input type="text" class="form-control" id="order" name="order" placeholder="數字">
                </div>
                <div class="form-group col-md-4">
                    <label for="name">名稱<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="名稱" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="sex">男女組别<span class="text-danger">*</span></label>
                    <select id="sex" class="form-control" name="group">
                        <option value="3" selected>男子組+女子組</option>
                        <option value="1">男子組</option>
                        <option value="2">女子組</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="people">每組派幾個<span class="text-danger">*</span></label>
                    <select id="people" class="form-control" name="people">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="sex">類别<span class="text-danger">*</span></label>
                    <select id="sex" class="form-control" name="type">
                        <option value="1" selected>徑賽</option>
                        <option value="2">田賽</option>
                        <option value="3">其他</option>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="limit" name="limit" checked>
                    <label class="form-check-label" for="limit">限制選手參賽項目</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y1" name="years[]" checked value="1">
                    <label class="form-check-label" for="y1">一年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y2" name="years[]" checked value="2">
                    <label class="form-check-label" for="y2">二年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y3" name="years[]" checked value="3">
                    <label class="form-check-label" for="y3">三年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y4" name="years[]" checked value="4">
                    <label class="form-check-label" for="y4">四年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y5" name="years[]" checked value="5">
                    <label class="form-check-label" for="y5">五年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y6" name="years[]" checked value="6">
                    <label class="form-check-label" for="y6">六年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y7" name="years[]" checked value="7">
                    <label class="form-check-label" for="y7">七年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y8" name="years[]" checked value="8">
                    <label class="form-check-label" for="y8">八年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y9" name="years[]" checked value="9">
                    <label class="form-check-label" for="y9">九年級　</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">新增</button>
        </form>
    </div>
@endsection
