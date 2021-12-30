@extends('layouts.master')

@section('title','報名比賽')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">開始報名「{{ $action->name }}」</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('class_teachers.sign_up') }}">報名比賽</a></li>
                <li class="breadcrumb-item active" aria-current="page">開始報名「{{ $action->name }}」</li>
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
                @if(count($items))
                    <form action="{{ route('class_teachers.sign_up_add') }}" method="post">
                        @csrf
                        <?php $n=0; ?>
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
                                                    <span class="badge badge-warning">個人賽</span>
                                                @endif
                                                @if($item->game_type=="group")
                                                    <span class="badge badge-primary">團體賽</span>
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
                                                @for($i=1;$i<=$item->people;$i++)
                                                <div class="col-3">
                                                    {{ Form::select('boy_select['.$i.']['.$item->id.']', $boys, null, ['id' => 'boy_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                </div>
                                                @endfor
                                            </div>
                                             @endif
                                            @if($item->game_type=="group")
                                            <div class="row">
                                                @for($j=1;$j<=$item->people;$j++)
                                                    <div class="col-4">
                                                        <div class="card">
                                                            <div class="card-header text-primary">
                                                                男子組{{ $j }}隊
                                                            </div>
                                                            <div class="card-body">
                                                                <label>正式選手</label>
                                                                @for($i=1;$i<=$item->official;$i++)
                                                                    {{ Form::select('boy_group_official_select['.$j.']['.$item->id.']['.$i.']', $boys, null, ['id' => 'boy_group_official_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                                @endfor
                                                                <hr>
                                                                <label>預備選手</label>
                                                                @for($i=1;$i<=$item->reserve;$i++)
                                                                    {{ Form::select('boy_group_reserve_select['.$j.']['.$item->id.']['.$i.']', $boys, null, ['id' => 'boy_group_reserve_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                                @endfor
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
                                                    @for($i=1;$i<=$item->people;$i++)
                                                        <div class="col-3">
                                                            {{ Form::select('girl_select['.$i.']['.$item->id.']', $girls, null, ['id' => 'girl_select', 'class' => 'form-control all_girl_select', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                        </div>
                                                    @endfor
                                                </div>
                                            @endif
                                            @if($item->game_type=="group")
                                                <div class="row">
                                                    @for($j=1;$j<=$item->people;$j++)
                                                        <div class="col-4">
                                                            <div class="card">
                                                                <div class="card-header text-danger">
                                                                    女子組{{ $j }}隊
                                                                </div>
                                                                <div class="card-body">
                                                                    <label>正式選手</label>
                                                                    @for($i=1;$i<=$item->official;$i++)
                                                                        {{ Form::select('girl_group_official_select['.$j.']['.$item->id.']['.$i.']', $girls, null, ['id' => 'girl_group_official_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                                    @endfor
                                                                    <hr>
                                                                    <label>預備選手</label>
                                                                    @for($i=1;$i<=$item->reserve;$i++)
                                                                        {{ Form::select('girl_group_reserve_select['.$j.']['.$item->id.']['.$i.']', $girls, null, ['id' => 'girl_group_reserve_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            @endif
                                        @endif
                                        @if($item->group==4)
                                            @if($item->game_type == "class")
                                                <div class="row">
                                                    <p>{{ $student_year }}年{{ $student_class }}班已參賽</p>
                                                    <span>不用報名</span>
                                                </div>
                                            @endif
                                            @if($item->game_type=="personal")
                                                <div class="row">
                                                    <text class="text-info col-12">不分性別組</text>
                                                    @for($i=1;$i<=$item->people;$i++)
                                                        <div class="col-3">
                                                            {{ Form::select('student_select['.$i.']['.$item->id.']', $all_students, null, ['id' => 'student_select', 'class' => 'form-control all_student_select', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                        </div>
                                                    @endfor
                                                </div>
                                            @endif
                                            @if($item->game_type=="group")
                                                <div class="row">
                                                    @for($j=1;$j<=$item->people;$j++)
                                                        <div class="col-4">
                                                            <div class="card">
                                                                <div class="card-header text-danger">
                                                                    不分性別組{{ $j }}隊
                                                                </div>
                                                                <div class="card-body">
                                                                    <label>正式選手</label>
                                                                    @for($i=1;$i<=$item->official;$i++)
                                                                        {{ Form::select('student_group_official_select['.$j.']['.$item->id.']['.$i.']', $all_students, null, ['id' => 'student_group_official_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                                    @endfor
                                                                    <hr>
                                                                    <label>預備選手</label>
                                                                    @for($i=1;$i<=$item->reserve;$i++)
                                                                        {{ Form::select('student_group_reserve_select['.$j.']['.$item->id.']['.$i.']', $all_students, null, ['id' => 'student_group_reserve_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','onchange'=>'count_st(this)','data-item_type'=>$item->type,'data-limit'=>$item->limit,'data-item_id'=>$item->id]) }}
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <?php $n++; ?>
                            @endif
                        @endforeach
                        <input type="hidden" name="action_id" value="{{ $action->id }}">
                        <input type="hidden" name="student_year" value="{{ $student_year }}">
                        <input type="hidden" name="student_class" value="{{ $student_class }}">
                        @if($n >0)
                            <button class="btn btn-primary" onclick="return confirm('確定嗎？')">送出</button>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </div>
    <script>
        function count_st(obj){
            var st = [];
            var st_track = [];
            var st_field = [];
            var st_item = [];

            @foreach($boys as $k=>$v)
                st[{{ $k }}] = 0;
                st_track[{{ $k }}] = 0;
                st_field[{{ $k }}] = 0;
            @endforeach
            @foreach($girls as $k=>$v)
                st[{{ $k }}] = 0;
                st_track[{{ $k }}] = 0;
                st_field[{{ $k }}] = 0;
            @endforeach
            @foreach($items as $item)
                st_item[{{ $item->id }}] = [];
            @endforeach

            id= obj.value;

            $('select').each(function(){
                if(this.value == id & this.dataset.limit==1){
                    st[id] = st[id] + 1;
                    if(this.dataset.item_type==1 & this.dataset.limit==1){
                        st_track[id] = st_track[id] + 1;
                    }
                    if(this.dataset.item_type==2 & this.dataset.limit==1){
                        st_field[id] = st_field[id] + 1;
                    }
                }
                if(this.value in st_item[this.dataset.item_id]) {
                    st_item[this.dataset.item_id][this.value] = st_item[this.dataset.item_id][this.value] + 1;
                }else{
                    st_item[this.dataset.item_id][this.value] = 1;
                }
            })


            if(st_item[obj.dataset.item_id][obj.value] > 1) {
                alert('該生報名過這個項目了，請選其他人');
                obj.value = "";
            }

            if(st[id] > {{ $action->frequency }}){
                alert('該生報名項目合計超過規定的 {{ $action->frequency }} 項，請選其他人');
                obj.value = "";
            }
            if(st_track[id] > {{ $action->track }}){
                alert('該生報名徑賽項目超過規定的 {{ $action->track }} 項，請選其他人');
                obj.value = "";
            }
            if(st_field[id] > {{ $action->field }}){
                alert('該生報名田賽項目合計超過規定的 {{ $action->field }} 項，請選其他人');
                obj.value = "";
            }
        }
    </script>
@endsection
