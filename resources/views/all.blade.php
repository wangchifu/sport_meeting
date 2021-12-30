@extends('layouts.master')

@section('title','全站資料')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">全站資料</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <form action="{{ route('show_one') }}" method="post" id="myform">
                    <div class="row">
                        <div class="col-8">
                            @csrf
                            <select class="form-control" name="school_code" required onchange="jump()">
                                <option value="">
                                    --請選擇學校--
                                </option>
                                @foreach($select_school as $k=>$v)
                                    <option value="{{ $k }}">
                                        {{ $k }} - {{ $schools[$k] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function jump(){
            $('#myform').submit();
        }
    </script>
@endsection
