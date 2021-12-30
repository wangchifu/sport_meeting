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
            <h2 class="text-success">每個學生徑賽最多報名 {{ $action->track }} 個項目</h2>
        </div>
        <div class="row">
            <h2 class="text-success">每個學生田賽最多報名 {{ $action->field }} 個項目</h2>
        </div>
        <div class="row">
            <h2 class="text-success">每個學生合計最多報名 {{ $action->frequency }} 個項目</h2>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <h3>{{ $student_year }}年{{ $student_class }}班</h3>
                @include('layouts.errors')
                @foreach($items as $item)
                    <?php
                    $years_array = unserialize($item->years);
                    ?>
                    @if(in_array($student_year,$years_array))
                        <div class="form-group">
                            <div class="container-fluid">
                                <label for="exampleFormControlSelect1">
                                    <h4>
                                        <?php $item_types = config('chcschool.item_types'); ?>
                                        {{ $item->name }} ({{ $item_types[$item->type] }})
                                        @if($item->game_type=="personal")
                                            <span class="badge badge-info">個人賽</span>
                                        @endif
                                        @if($item->game_type=="group")
                                            <span class="badge badge-info">團體賽</span>
                                        @endif
                                        @if($item->game_type=="class")
                                            <span class="badge badge-info">班際賽</span>
                                        @endif
                                        @if($item->limit)
                                            <small class="text-danger">(限報)</small>
                                        @endif
                                    </h4>
                                </label>
                                @if($item->group==1 or $item->group==3)
                                    @if($item->game_type=="personal")
                                        <div class="row">
                                            <text class="text-primary col-12">男子組</text>
                                            <div class="col-3">
                                                <?php
                                                $boys_sign = \App\StudentSign::where('action_id',$action->id)->where('item_id',$item->id)->where('sex','男')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                                ?>
                                                @foreach($boys_sign as $boy_sign)
                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $boy_sign->student_id }}">{{ $boy_sign->student->name }}</span>
                                                    @if($action->disable <> 1)
                                                        <a href="{{ route('class_teachers.sign_up_delete',$boy_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $boy_sign->student->name }}" data-sign_id="{{ $boy_sign->id }}" data-sex="男" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                    @endif
                                                @endforeach
                                                @if(count($boys_sign) < $item->people)
                                                    @for($i=1;$i<=$item->people-count($boys_sign);$i++)
                                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="男" data-item_name="{{ $item->name }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if($item->game_type=="group")
                                        <div class="row">
                                            @for($j=1;$j<=$item->people;$j++)
                                                <div class="col-6">
                                                    <?php
                                                    $boys_sign = \App\StudentSign::where('action_id',$action->id)->where('item_id',$item->id)->where('sex','男')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                                    ?>
                                                    <div class="card">
                                                        <div class="card-header text-primary">
                                                            男子組{{ $j }}隊
                                                        </div>
                                                        <div class="card-body">
                                                            <label>正式選手</label><br>
                                                            <?php $boy_official=0; ?>
                                                            @foreach($boys_sign as $boy_sign)
                                                                @if($boy_sign->is_official==1 and $boy_sign->group_num==$j)
                                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $boy_sign->student_id }}">{{ $boy_sign->student->name }}</span>
                                                                    @if($action->disable <> 1)
                                                                        <a href="{{ route('class_teachers.sign_up_delete',$boy_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $boy_sign->student->name }}" data-sign_id="{{ $boy_sign->id }}" data-sex="男" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                                    @endif
                                                                    <?php $boy_official++; ?>
                                                                @endif
                                                            @endforeach
                                                            @if($boy_official < $item->official)
                                                                @for($i=1;$i<=$item->official-$boy_official;$i++)
                                                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="男" data-item_name="{{ $item->name }}" data-is_official="1" data-group_num="{{ $j }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                                @endfor
                                                            @endif
                                                            <hr>
                                                            <label>預備選手</label><br>
                                                            <?php $boy_reserve=0; ?>
                                                            @foreach($boys_sign as $boy_sign)
                                                                @if($boy_sign->is_official==null and $boy_sign->group_num==$j)
                                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $boy_sign->student_id }}">{{ $boy_sign->student->name }}</span>
                                                                    @if($action->disable <> 1)
                                                                        <a href="{{ route('class_teachers.sign_up_delete',$boy_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $boy_sign->student->name }}" data-sign_id="{{ $boy_sign->id }}" data-sex="男" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                                    @endif
                                                                    <?php $boy_reserve++; ?>
                                                                @endif
                                                            @endforeach
                                                            @if($boy_reserve < $item->reserve)
                                                                @for($i=1;$i<=$item->reserve-$boy_reserve;$i++)
                                                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="男" data-item_name="{{ $item->name }}" data-group_num="{{ $j }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    @endif

                                    <div class="row">
                                        　
                                    </div>
                                @endif
                                @if($item->group==2 or $item->group==3)
                                    @if($item->game_type=="personal")
                                        <div class="row">
                                            <text class="text-danger col-12">女子組</text>
                                            <div class="col-3">
                                                <?php
                                                $girls_sign = \App\StudentSign::where('action_id',$action->id)->where('item_id',$item->id)->where('sex','女')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                                ?>
                                                @foreach($girls_sign as $girl_sign)
                                                        <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $girl_sign->student_id }}">{{ $girl_sign->student->name }}</span>
                                                        @if($action->disable <> 1)
                                                            <a href="{{ route('class_teachers.sign_up_delete',$girl_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $girl_sign->student->name }}" data-sign_id="{{ $girl_sign->id }}" data-sex="女" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                        @endif
                                                @endforeach
                                                @if(count($girls_sign) < $item->people)
                                                    @for($i=1;$i<=$item->people-count($girls_sign);$i++)
                                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="女" data-item_name="{{ $item->name }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if($item->game_type=="group")
                                        <div class="row">
                                            @for($j=1;$j<=$item->people;$j++)
                                                <div class="col-6">
                                                    <?php
                                                    $girls_sign = \App\StudentSign::where('action_id',$action->id)->where('item_id',$item->id)->where('sex','女')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                                    ?>
                                                    <div class="card">
                                                        <div class="card-header text-danger">
                                                            女子組{{ $j }}隊
                                                        </div>
                                                        <div class="card-body">
                                                            <label>正式選手</label><br>
                                                            <?php $girl_official=0; ?>
                                                            @foreach($girls_sign as $girl_sign)
                                                                @if($girl_sign->is_official==1 and $girl_sign->group_num==$j)
                                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $girl_sign->student_id }}">{{ $girl_sign->student->name }}</span>
                                                                    @if($action->disable <> 1)
                                                                        <a href="{{ route('class_teachers.sign_up_delete',$girl_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $girl_sign->student->name }}" data-sign_id="{{ $girl_sign->id }}" data-sex="女" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                                    @endif
                                                                    <?php $girl_official++; ?>
                                                                @endif
                                                            @endforeach
                                                            @if($girl_official < $item->official)
                                                                @for($i=1;$i<=$item->official-$girl_official;$i++)
                                                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="女" data-item_name="{{ $item->name }}" data-is_official="1" data-group_num="{{ $j }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                                @endfor
                                                            @endif
                                                            <hr>
                                                            <label>預備選手</label><br>
                                                            <?php $girl_reserve=0; ?>
                                                            @foreach($girls_sign as $girl_sign)
                                                                @if($girl_sign->is_official==null and $girl_sign->group_num==$j)
                                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $girl_sign->student_id }}">{{ $girl_sign->student->name }}</span>
                                                                    @if($action->disable <> 1)
                                                                        <a href="{{ route('class_teachers.sign_up_delete',$girl_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $girl_sign->student->name }}" data-sign_id="{{ $girl_sign->id }}" data-sex="女" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                                    @endif
                                                                    <?php $girl_reserve++; ?>
                                                                @endif
                                                            @endforeach
                                                            @if($girl_reserve < $item->reserve)
                                                                @for($i=1;$i<=$item->reserve-$girl_reserve;$i++)
                                                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="女" data-item_name="{{ $item->name }}" data-group_num="{{ $j }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    @endif
                                @endif
                                @if($item->group==4)
                                    @if($item->game_type=="personal")
                                        <div class="row">
                                            <text class="text-info col-12">不分性別組</text>
                                            <div class="col-3">
                                                <?php
                                                $students_sign = \App\StudentSign::where('action_id',$action->id)->where('item_id',$item->id)->where('sex','4')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                                ?>
                                                @foreach($students_sign as $student_sign)
                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $student_sign->student_id }}">{{ $student_sign->student->name }}</span>
                                                    @if($action->disable <> 1)
                                                        <a href="{{ route('class_teachers.sign_up_delete',$student_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $student_sign->student->name }}" data-sign_id="{{ $student_sign->id }}" data-sex="4" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                    @endif
                                                @endforeach
                                                @if(count($students_sign) < $item->people)
                                                    @for($i=1;$i<=$item->people-count($students_sign);$i++)
                                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="4" data-item_name="{{ $item->name }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if($item->game_type=="group")
                                        <div class="row">
                                            @for($j=1;$j<=$item->people;$j++)
                                                <div class="col-6">
                                                    <?php
                                                    $students_sign = \App\StudentSign::where('action_id',$action->id)->where('item_id',$item->id)->where('sex','4')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                                    ?>
                                                    <div class="card">
                                                        <div class="card-header text-danger">
                                                            不分性別組{{ $j }}隊
                                                        </div>
                                                        <div class="card-body">
                                                            <label>正式選手</label><br>
                                                            <?php $student_official=0; ?>
                                                            @foreach($students_sign as $student_sign)
                                                                @if($student_sign->is_official==1 and $student_sign->group_num==$j)
                                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $student_sign->student_id }}">{{ $student_sign->student->name }}</span>
                                                                    @if($action->disable <> 1)
                                                                        <a href="{{ route('class_teachers.sign_up_delete',$student_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $student_sign->student->name }}" data-sign_id="{{ $student_sign->id }}" data-sex="4" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                                    @endif
                                                                    <?php $student_official++; ?>
                                                                @endif
                                                            @endforeach
                                                            @if($student_official < $item->official)
                                                                @for($i=1;$i<=$item->official-$student_official;$i++)
                                                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="4" data-item_name="{{ $item->name }}" data-is_official="1" data-group_num="{{ $j }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                                @endfor
                                                            @endif
                                                            <hr>
                                                            <label>預備選手</label><br>
                                                            <?php $student_reserve=0; ?>
                                                            @foreach($students_sign as $student_sign)
                                                                @if($student_sign->is_official==null and $student_sign->group_num==$j)
                                                                    <span class="st_name" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}" data-id="{{ $student_sign->student_id }}">{{ $student_sign->student->name }}</span>
                                                                    @if($action->disable <> 1)
                                                                        <a href="{{ route('class_teachers.sign_up_delete',$student_sign->id) }}" onclick="return confirm('確定刪除嗎？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changeModal" data-name="{{ $student_sign->student->name }}" data-sign_id="{{ $student_sign->id }}" data-sex="4" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">更換</button>,
                                                                    @endif
                                                                    <?php $student_reserve++; ?>
                                                                @endif
                                                            @endforeach
                                                            @if($student_reserve < $item->reserve)
                                                                @for($i=1;$i<=$item->reserve-$student_reserve;$i++)
                                                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#makeModal" data-item_id="{{ $item->id }}" data-sex="4" data-item_name="{{ $item->name }}" data-group_num="{{ $j }}" data-item_type="{{ $item->type }}" data-limit="{{ $item->limit }}">補登</button>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    @endif
                                @endif
                                @if($item->game_type=="class")
                                    <p>{{ $student_year }}年{{ $student_class }}班已參賽</p>
                                @endif
                            </div>
                        </div>
                        <hr>
                    @endif
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
                        <input type="hidden" id="is_official" name="is_official">
                        <input type="hidden" id="group_num" name="group_num">
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
            var all_students = {
                <?php
                foreach($all_students as $k=>$v){
                    echo $k.":'$v',";
                }
                ?>
            };
            var button = $(event.relatedTarget) // Button that triggered the modal
            var name = button.data('name');
            var sign_id = button.data('sign_id');
            var sex = button.data('sex');
            var item_type = button.data('item_type');
            var limit = button.data('limit');
            i = item_type;
            l = limit;

            boys_select = "<select class='form-control' name='student_id' onchange='count_st(this,i,l)'><option value=''>--請選擇--</option>";
            girls_select = "<select class='form-control' name='student_id' onchange='count_st(this,i,l)'><option value=''>--請選擇--</option>";
            all_students_select = "<select class='form-control' name='student_id' onchange='count_st(this,i,l)'><option value=''>--請選擇--</option>";

            for(var key in boys) {
                boys_select += "<option value='"+key+"'>"+boys[key]+"</option>";
            }
            for(var key in girls) {
                girls_select += "<option value='"+key+"'>"+girls[key]+"</option>";
            }
            for(var key in all_students) {
                all_students_select += "<option value='"+key+"'>"+all_students[key]+"</option>";
            }

            boys_select += "</select>";
            girls_select += "</select>";
            all_students_select += "</select>";

            if(sex == '男'){
                $('#showText').html('更換 ['+name+'] 成：'+boys_select);
            }
            if(sex == '女'){
                $('#showText').html('更換 ['+name+'] 成：'+girls_select);
            }
            if(sex == '4'){
                $('#showText').html('更換 ['+name+'] 成：'+all_students_select);
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
            var all_students = {
                <?php
                foreach($all_students as $k=>$v){
                    echo $k.":'$v',";
                }
                ?>
            };
            var button = $(event.relatedTarget) // Button that triggered the modal
            var item_id = button.data('item_id');
            var item_name = button.data('item_name');
            var is_official = button.data('is_official');
            var group_num = button.data('group_num');
            var sex = button.data('sex');
            var item_type = button.data('item_type');
            var limit = button.data('limit');
            i = item_type;
            l = limit;


            boys_select = "<select class='form-control' name='student_id' onchange='count_st(this,i,l)'><option value=''>--請選擇--</option>";
            girls_select = "<select class='form-control' name='student_id' onchange='count_st(this,i,l)'><option value=''>--請選擇--</option>";
            all_students_select = "<select class='form-control' name='student_id' onchange='count_st(this,i,l)'><option value=''>--請選擇--</option>";

            for(var key in boys) {
                boys_select += "<option value='"+key+"'>"+boys[key]+"</option>";
            }
            for(var key in girls) {
                girls_select += "<option value='"+key+"'>"+girls[key]+"</option>";
            }
            for(var key in all_students) {
                all_students_select += "<option value='"+key+"'>"+all_students[key]+"</option>";
            }

            boys_select += "</select>";
            girls_select += "</select>";
            all_students_select += "</select>";

            if(sex == '男'){
                $('#showText2').html(item_name+boys_select);
            }
            if(sex == '女'){
                $('#showText2').html(item_name+girls_select);
            }
            if(sex == '4'){
                $('#showText2').html(item_name+all_students_select);
            }
            $('#item_id').val(item_id);
            $('#is_official').val(is_official);
            $('#group_num').val(group_num);
        })
        });

        function count_st(obj,item_type,limit){
            var st = [];
            var st_track = [];
            var st_field = [];
            @foreach($all_students as $k=>$v)
                st[{{ $k }}] = 0;
                st_track[{{ $k }}] = 0;
                st_field[{{ $k }}] = 0;
            @endforeach

            $('.st_name').each(function(){
                if(this.dataset.limit==1){
                    st[this.dataset.id] = st[this.dataset.id] + 1;
                }

                if(this.dataset.item_type==1 & this.dataset.limit==1){
                    st_track[this.dataset.id] = st_track[this.dataset.id] + 1;
                }
                if(this.dataset.item_type==2 & this.dataset.limit==1){
                    st_field[this.dataset.id] = st_field[this.dataset.id] + 1;
                }
            });

            id= obj.value;

            if(limit==1){
                a = st[id]+1;
            }

            if(item_type==1 & limit==1){
                t = st_track[id] + 1;
            }
            if(item_type==2 & limit==1){
                f = st_field[id] + 1;
            }


            if(a > {{ $action->frequency }}){
                alert('該生報名項目合計超過規定的 {{ $action->frequency }} 項，請選其他人');
                obj.value = "";
            }

            if(t > {{ $action->track }}){
                alert('該生報名徑賽項目超過規定的 {{ $action->track }} 項，請選其他人');
                obj.value = "";
            }
            if(f > {{ $action->field }}){
                alert('該生報名田賽項目合計超過規定的 {{ $action->field }} 項，請選其他人');
                obj.value = "";
            }

        }

    </script>
@endsection
