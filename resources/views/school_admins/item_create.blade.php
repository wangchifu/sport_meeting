@extends('layouts.master')

@section('title','新增比賽項目')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">新增「{{ $action->name }}」比賽項目</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.action') }}">「報名任務」列表</a></li>
                <li class="breadcrumb-item"><a href="{{ route('school_admins.item') }}">比賽項目</a></li>
                <li class="breadcrumb-item active" aria-current="page">新增比賽項目</li>
            </ol>
        </nav>
        <form action="{{ route('school_admins.item_add') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="personal" value="personal" name="game_type" checked onchange="hide()">
                    <label class="form-check-label" for="personal"><i class="fas fa-user"></i> 個人賽</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="group" value="group" name="game_type" onchange="show()">
                    <label class="form-check-label" for="group"><i class="fas fa-users"></i> 團體賽</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="class" value="class" name="game_type" onchange="hide2()">
                    <label class="form-check-label" for="class"><i class="fas fa-school"></i> 班際賽</label>
                </div>
            </div>
            <div class="form-row" style="display:none;background-color:#F0F0F0" id="official_reserve">
                <div class="form-group col-md-6">
                    <label for="order">正式選手數</label>
                    <input type="number" class="form-control" id="official" name="official" placeholder="數字">
                </div>
                <div class="form-group col-md-6">
                    <label for="order">預備選手數</label>
                    <input type="number" class="form-control" id="reserve" name="reserve" placeholder="數字">
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="form-group col-md-1">
                    <label for="order">排序</label>
                    <input type="number" class="form-control" id="order" name="order" placeholder="數字">
                </div>
                <div class="form-group col-md-3">
                    <label for="name">名稱<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="名稱" required>
                </div>
                <div class="form-group col-md-3" id="sex_select">
                    <label for="group">男女組别<span class="text-danger">*</span></label>
                    <select id="group" class="form-control" name="group">
                        <option value="3" selected>男子組+女子組</option>
                        <option value="1">男子組</option>
                        <option value="2">女子組</option>
                        <option value="4">不分性別</option>
                    </select>
                </div>
                <div class="form-group col-md-2" id="people_select">
                    <label for="people">每班每組派幾個(隊)<span class="text-danger">*</span></label>
                    <select id="people" class="form-control" name="people">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="reward">錄取名次<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="reward" name="reward" placeholder="數字" required value="5">
                </div>
                <div class="form-group col-md-1">
                    <label for="sex">類别<span class="text-danger">*</span></label>
                    <select id="sex" class="form-control" name="type">
                        <option value="1" selected>徑賽</option>
                        <option value="2">田賽</option>
                        <option value="3">其他</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="limit" name="limit" checked>
                    <label class="form-check-label" for="limit">限制選手參賽項目</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="u1" name="years[]"  value="幼小">
                    <label class="form-check-label" for="u1">幼小　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="u2" name="years[]"  value="幼中">
                    <label class="form-check-label" for="u2">幼中　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="u3" name="years[]"  value="幼大">
                    <label class="form-check-label" for="u3">幼大　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y1" name="years[]"  value="1">
                    <label class="form-check-label" for="y1">一年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y2" name="years[]"  value="2">
                    <label class="form-check-label" for="y2">二年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y3" name="years[]"  value="3">
                    <label class="form-check-label" for="y3">三年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y4" name="years[]"  value="4">
                    <label class="form-check-label" for="y4">四年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y5" name="years[]"  value="5">
                    <label class="form-check-label" for="y5">五年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y6" name="years[]"  value="6">
                    <label class="form-check-label" for="y6">六年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y7" name="years[]"  value="7">
                    <label class="form-check-label" for="y7">七年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y8" name="years[]"  value="8">
                    <label class="form-check-label" for="y8">八年級　</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="y9" name="years[]"  value="9">
                    <label class="form-check-label" for="y9">九年級　</label>
                </div>
            </div>
            <input type="hidden" name="action_id" value="{{ $action->id }}">
            <button type="submit" class="btn btn-primary" onclick="return confirm('確定嗎？')">新增</button>
        </form>
    </div>
    <script>
        function show(){
            $('#official_reserve').show();
            $('#sex_select').show();
            $('#people_select').show();
        }
        function hide(){
            $('#official_reserve').hide();
            $('#sex_select').show();
            $('#people_select').show();
        }
        function hide2(){
            $('#official_reserve').hide();
            //document.getElementById('people').value = 1;
            //document.getElementById('sex').value = 4;
            $('#sex_select').hide();
            $('#people_select').hide();
        }
    </script>
@endsection
