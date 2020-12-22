@extends('layouts.master')

@section('title','報名比賽結果')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">「{{ $action->name }}」報名結果</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('class_teachers.sign_up') }}">報名比賽</a></li>
                <li class="breadcrumb-item active" aria-current="page">「{{ $action->name }}」報名結果</li>
            </ol>
        </nav>
        <div class="row">
            <h2 class="text-success">每個學生最多報名 {{ $action->frequency }} 個項目</h2>
            <div class="col-xl-12 col-md-12">
                <h3>{{ $student_year }}年{{ $student_class }}班</h3>
                @include('layouts.errors')
                @foreach($items as $item)
                    <div class="form-group">
                        <div class="container-fluid">
                            <label for="exampleFormControlSelect1"><h4>{{ $item->name }}@if($item->limit) <small class="text-danger">(限報)</small> @endif</h4></label>
                            @if($item->group==1 or $item->group==3)
                            <div class="row">
                                <text class="text-primary col-12">男子組</text>
                                <div class="col-3">
                                <?php
                                    $boys_sign = \App\StudentSign::where('item_id',$item->id)->where('sex','男')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                ?>
                                @foreach($boys_sign as $boy_sign)
                                    {{ $boy_sign->student->name }} <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $boy_sign->student->name }}" data-sign_id="{{ $boy_sign->id }}" data-sex="男">更換</button>,
                                @endforeach
                                @if(count($boys_sign) < $item->people)
                                    @for($i=1;$i<=$item->people-count($boys_sign);$i++)
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="男" data-item_name="{{ $item->name }}">補登</button>
                                    @endfor
                                @endif
                                </div>
                            </div>
                            <div class="row">
                                　
                            </div>
                            @endif
                            @if($item->group==2 or $item->group==3)
                            <div class="row">
                                <text class="text-danger col-12">女子組</text>
                                <div class="col-3">
                                    <?php
                                    $girls_sign = \App\StudentSign::where('item_id',$item->id)->where('sex','女')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                    ?>
                                @foreach($girls_sign as $girl_sign)
                                    {{ $girl_sign->student->name }} <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $girl_sign->student->name }}" data-sign_id="{{ $girl_sign->id }}" data-sex="女">更換</button>,
                                @endforeach
                                @if(count($girls_sign) < $item->people)
                                    @for($i=1;$i<=$item->people-count($girls_sign);$i++)
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="女" data-item_name="{{ $item->name }}">補登</button>
                                    @endfor
                                @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="changeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeModalLabel">請確認</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('class_teachers.student_sign_update') }}" method="post" id="change_form">
                        <input type="hidden" name="action_id" value="{{ $action->id }}">
                        <input type="hidden" id="student_sign_id" name="student_sign_id">
                        @csrf
                        @method('patch')
                    <span id="showText"></span>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                    <button id="do" class="btn btn-primary" onclick="document.getElementById('change_form').submit()">確定</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="makeModal" tabindex="-1" role="dialog" aria-labelledby="makeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="makeModalLabel">請確認</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('class_teachers.student_sign_make') }}" method="post" id="make_form">
                        <input type="hidden" name="action_id" value="{{ $action->id }}">
                        <input type="hidden" id="item_id" name="item_id">
                        @csrf
                        <span id="showText2"></span>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                    <button id="do" class="btn btn-primary" onclick="document.getElementById('make_form').submit()">確定</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () { $('#changeModal').on('show.bs.modal', function (event) {
            var boys = {
                <?php
                    foreach($boys as $k=>$v){
                        echo $k.":'$v',";
                    }
                ?>
            };
            var girls = {
                <?php
                foreach($girls as $k=>$v){
                    echo $k.":'$v',";
                }
                ?>
            };
            boys_select = "<select class='form-control' name='student_id'>";
            girls_select = "<select class='form-control' name='student_id'>";

            for(var key in boys) {
                boys_select += "<option value='"+key+"'>"+boys[key]+"</option>";
            }
            for(var key in girls) {
                girls_select += "<option value='"+key+"'>"+girls[key]+"</option>";
            }

            boys_select += "</select>";
            girls_select += "</select>";

            var button = $(event.relatedTarget) // Button that triggered the modal
            var name = button.data('name');
            var sign_id = button.data('sign_id');
            var sex = button.data('sex');
            if(sex == '男'){
                $('#showText').html('更換 ['+name+'] 成：'+boys_select);
            }
            if(sex == '女'){
                $('#showText').html('更換 ['+name+'] 成：'+girls_select);
            }
            $('#student_sign_id').val(sign_id);
            })
        });

        $(function () { $('#makeModal').on('show.bs.modal', function (event) {
            var boys = {
                <?php
                foreach($boys as $k=>$v){
                    echo $k.":'$v',";
                }
                ?>
            };
            var girls = {
                <?php
                foreach($girls as $k=>$v){
                    echo $k.":'$v',";
                }
                ?>
            };
            boys_select = "<select class='form-control' name='student_id'>";
            girls_select = "<select class='form-control' name='student_id'>";

            for(var key in boys) {
                boys_select += "<option value='"+key+"'>"+boys[key]+"</option>";
            }
            for(var key in girls) {
                girls_select += "<option value='"+key+"'>"+girls[key]+"</option>";
            }

            boys_select += "</select>";
            girls_select += "</select>";

            var button = $(event.relatedTarget) // Button that triggered the modal
            var item_id = button.data('item_id');
            var item_name = button.data('item_name');
            var sex = button.data('sex');
            if(sex == '男'){
                $('#showText2').html(item_name+boys_select);
            }
            if(sex == '女'){
                $('#showText2').html(item_name+girls_select);
            }
            $('#item_id').val(item_id);
        })
        });

    </script>
@endsection
