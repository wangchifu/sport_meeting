@extends('layouts.master')

@section('title','自訂獎狀')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">自訂獎狀</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <h3>
                    學校獎狀範本上傳
                </h3>
                <form method="post" action="{{ route('school_scores.demo_upload') }}" enctype="multipart/form-data">
                    @csrf
                    {{ Form::file('demo', ['class' => 'form-control','required'=>'required','accept'=>'.odt']) }}
                    <br>
                    <a href="{{ asset('demo.odt') }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> 範本下載</a>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> 上傳自校範本</button>
                    @if(is_file(storage_path('app/public').'/'.auth()->user()->code.'/demo.odt'))
                        [<a href="{{ asset('storage/'.auth()->user()->code.'/demo.odt') }}">已上傳</a>]
                    @endif
                </form>
                <hr>
                <h3>例外列印</h3>
                <div style="border:1px black solid;padding: 10px" class="col-4">
                    <h2 class="text-center">獎 狀</h2>
                    <form action="{{ route('school_scores.print_extra') }}" method="post">
                        @csrf
                        <table>
                            <tr><td>查 <input type="text" name="this_student" value="年班同學"></td></tr>
                            <tr><td>參加 <input type="text" name="action_name" value="{{ substr(auth()->user()->semester,0,3) }}學年度校慶運動會"></td></tr>
                            <tr><td>表現優異</td></tr>
                            <tr><td>組別：<input type="text" name="group"></td></tr>
                            <tr><td>項目：<input type="text" name="item"></td></tr>
                            <tr><td>名次：<input type="text" name="ranking"></td></tr>
                            <tr><td>成績：<input type="text" name="score"></td></tr>
                            <tr><td>特頒此狀以資鼓勵</td></tr>
                        </table>
                        <div style="border:10px red solid;" class="col-3">
                            　<br>
                            　
                        </div>
                        <br>
                        <div class="col-9 text-right">
                            <input type="text" name="print_date" value="中華民國{{ date('Y')-1911 }}年{{ date('m') }}月{{ date('d') }}日">
                        </div>
                        <br>
                        <input type="submit" value="送出">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function jump(){
            if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
                location="/school_scores/score_print/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
