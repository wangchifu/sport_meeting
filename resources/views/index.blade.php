@extends('layouts.master')

@section('title','首頁')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="text-center">
                    <h1>
                        {{ env('APP_NAME') }}
                    </h1>
                    <figure class="figure col-6">
                        <img src="{{ asset('images/logo.jpg') }}" class="figure-img img-fluid rounded" alt="...">
                        <figcaption class="figure-caption">校園運動 PNG由588ku设计 <a href="https://zh.pngtree.com" target="_blank">Pngtree.com</a></figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </div>
@endsection
