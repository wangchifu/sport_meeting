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
            <h2 class="text-success">每個學生最多報名 {{ $action->frequency }} 個項目</h2>
            <div class="col-xl-12 col-md-12">
                <h3>{{ $student_year }}年{{ $student_class }}班</h3>
                <form action="{{ route('class_teachers.sign_up_add') }}" method="post">
                    @csrf
                    @foreach($items as $item)
                        <div class="form-group">
                            <div class="container-fluid">
                                <label for="exampleFormControlSelect1"><h4>{{ $item->name }}</h4></label>
                                @if($item->group==1 or $item->group==3)
                                <div class="row">
                                    <text class="text-primary col-12">男子組</text>
                                    @for($i=1;$i<=$item->people;$i++)
                                    <div class="col-3">
                                        {{ Form::select('boy_select['.$i.']['.$item->id.']', $boys, null, ['id' => 'boy_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','required' => 'required']) }}
                                    </div>
                                    @endfor
                                </div>
                                <div class="row">
                                    　
                                </div>
                                @endif
                                @if($item->group==2 or $item->group==3)
                                <div class="row">
                                    <text class="text-danger col-12">女子組</text>
                                    @for($i=1;$i<=$item->people;$i++)
                                        <div class="col-3">
                                            {{ Form::select('girl_select['.$i.']['.$item->id.']', $girls, null, ['id' => 'girl_select', 'class' => 'form-control', 'placeholder' => '--請選擇--','required' => 'required']) }}
                                        </div>
                                    @endfor
                                </div>
                                @endif
                            </div>
                        </div>
                        <hr>
                    @endforeach
                    <input type="hidden" name="action_id" value="{{ $action->id }}">
                    <input type="hidden" name="student_year" value="{{ $student_year }}">
                    <input type="hidden" name="student_class" value="{{ $student_class }}">
                    <button class="btn btn-primary">送出</button>
                </form>
            </div>
        </div>
    </div>
@endsection
