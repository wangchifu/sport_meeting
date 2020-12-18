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
                    @foreach($items as $item)
                        <div class="form-group">
                            <div class="container-fluid">
                                <label for="exampleFormControlSelect1"><h4>{{ $item->name }}</h4></label>
                                @if($item->group==1 or $item->group==3)
                                <div class="row">
                                    <text class="text-primary col-12">男子組</text>
                                    <div class="col-3">
                                    <?php
                                        $boys = \App\StudentSign::where('item_id',$item->id)->where('sex','男')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                    ?>
                                    @foreach($boys as $boy)
                                        {{ $boy->student->name }} <button class="badge badge-primary" onclick="javascript:location.href='http://www.wibibi.com'">更換</button>,
                                    @endforeach
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
                                        $girls = \App\StudentSign::where('item_id',$item->id)->where('sex','女')->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                        ?>
                                        @foreach($girls as $girl)
                                            {{ $girl->student->name }},
                                        @endforeach
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
@endsection
