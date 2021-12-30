@extends('layouts.master')

@section('title','學生 Excel 匯入')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">使用 Excel 匯入學生資料</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <a href="{{ route('school_admins.api') }}" class="btn btn-secondary btn-sm">返回</a>
                <br>
                @include('layouts.errors')
                {{ Form::open(['route' => 'school_admins.do_import', 'method' => 'post','files'=>true]) }}
                <div class="form-group">
                    <label for="semester">
                        四碼學期代號 <strong class="text-danger">*</strong>
                        <small class="text-secondary">(如：1101)</small>
                    </label>
                    {{ Form::number('semester',null, ['id'=>'semester','class' => 'form-control','required'=>'required','maxlength'=>'4']) }}
                </div>
                <div class="form-group">
                    <label for="file">
                        學生名冊 <strong class="text-danger">*</strong>
                        <small class="text-secondary">(限 xlsx 檔)</small>
                    </label>
                    {{ Form::file('file', ['class' => 'form-control','required'=>'required','accept'=>'.xlsx']) }}
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-info btn-sm" onclick="return confirm('確定送出嗎？請耐心等待，不要狂按')"><i class="fas fa-forward"></i> 大量匯入</button>
                </div>
                {{ Form::close() }}
                <hr>
                <h3>設定參考</h3>
                方式1.<a href="{{ asset('list.xlsx') }}" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-download"></i> 下載範本檔手填</a>
                <br>
                方式2.<a href="https://cloudschool.chc.edu.tw" target="_blank">cloudschool</a> > 註冊組  > 學生資料管理  >  報表列印  >  名冊輸出
                <br>
                依序選出欄位：<br>
                姓名<br>
                性別<br>
                年級(數字)<br>
                班序(數字)<br>
                座號<br>
                導師姓名<br>
                <img src="{{ asset('images/import.png') }}" class="img-fluid">
            </div>
        </div>
    </div>
@endsection
