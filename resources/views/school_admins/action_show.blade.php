@extends('layouts.master')

@section('title','報名狀況')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">{{ $action->name }} 報名狀況</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.action') }}">報名設定</a></li>
                <li class="breadcrumb-item active" aria-current="page">報名狀況</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl-12 col-md-12">


            </div>
        </div>
    </div>
@endsection
