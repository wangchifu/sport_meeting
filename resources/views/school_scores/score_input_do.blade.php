@extends('layouts.master')

@section('title',$item->name .'成績登入')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">{{ $item->name }}成績登入</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_scores.score_input') }}">成績登入</a></li>
                <li class="breadcrumb-item active" aria-current="page">開始登入「{{ $action->name }}」 - {{ $item->name }}</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <?php
                    $years = unserialize($item->years);
                ?>
                @if($item->group == 1 or $item->group == 3)
                    @foreach($years as $year)
                    <h2>
                        {{ $year }}年級-{{ $item->name }} 男子組
                    </h2>
                    <form method="post" action="{{ route('school_scores.score_input_update') }}">
                    @csrf
                    <table class="table table-striped">
                        <thead class="table-primary">
                        <tr>
                            <th>
                                學生
                            </th>
                            <th>
                                成績
                            </th>
                            <th>
                                名次 <input type="checkbox" checked name="checkbox" id="itemB{{ $year }}"> <label for="itemB{{ $year }}">自動排名</label>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($student_array[$year]['男']))
                            @foreach($student_array[$year]['男'] as $k=>$v)
                                <tr>
                                    <td>
                                        <img src="{{ asset('images/boy.gif') }}">{{ $v['number'] }} {{ $v['name'] }}
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $v['achievement'] }}" name="achievement[{{ $v['id'] }}]" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" value="{{ $v['ranking'] }}" name="ranking[{{ $v['id'] }}]" placeholder="自動排名時可不填" tabindex="-1">
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                        <input type="hidden" name="action_id" value="{{ $action->id }}">
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <button class="btn btn-primary">送出{{ $year }}年級男子組成績</button>
                    </form>
                    <hr>
                @endforeach
                @endif
                @if($item->group == 2 or $item->group == 3)
                    @foreach($years as $year)
                        <h2>
                            {{ $year }}年級-{{ $item->name }} 女子組
                        </h2>
                        <form method="post" action="{{ route('school_scores.score_input_update') }}">
                        @csrf
                        <table class="table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th>
                                    學生
                                </th>
                                <th>
                                    成績
                                </th>
                                <th>
                                    名次 <input type="checkbox" checked name="checkbox" id="itemG{{ $year }}"> <label for="itemG{{ $year }}">自動排名</label>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($student_array[$year]['女']))
                                @foreach($student_array[$year]['女'] as $k=>$v)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('images/girl.gif') }}">{{ $v['number'] }} {{ $v['name'] }}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $v['achievement'] }}" name="achievement[{{ $v['id'] }}]" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" value="{{ $v['ranking'] }}" name="ranking[{{ $v['id'] }}]" placeholder="自動排名時可不填" tabindex="-1">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                            <input type="hidden" name="action_id" value="{{ $action->id }}">
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button class="btn btn-primary">送出{{ $year }}年級女子組成績</button>
                        </form>
                        <hr>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@endsection
