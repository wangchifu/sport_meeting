@extends('layouts.master')

@section('title','報名比賽')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">{{ $student_year }}年{{ $student_class }}班報名比賽</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <table class="table table-striped">
                    <thead class="table-primary">
                    <tr>
                        <th>
                            報名期限
                        </th>
                        <th>
                            名稱
                        </th>
                        <th>
                            動作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; ?>
                    @foreach($actions as $action)
                        <tr>
                            <td>
                                {{ $action->started_at }}<br>
                                {{ $action->stopped_at }}
                            </td>
                            <td>
                                {{ $action->name }}
                                @if($action->disable==1)
                                    <span class="text-danger">[已停止報名]</span>
                                @endif
                            </td>
                            <td>
                                <?php
                                    $items_array = \App\Item::where('action_id',$action->id)
                                        ->get()->pluck('id')->toArray();
                                    $check = \App\StudentSign::whereIn('item_id',$items_array)
                                        ->where('student_year',$student_year)
                                        ->where('student_class',$student_class)
                                        ->where('game_type','<>','class')
                                        ->first();
                                ?>
                                @if(empty($check))
                                    <a href="{{ route('class_teachers.sign_up_do',$action->id) }}" class="btn btn-primary btn-sm">報名</a>
                                @else
                                    已報過名 <a href="{{ route('class_teachers.sign_up_show',$action->id) }}" class="btn btn-info btn-sm">詳細資料...</a>
                                @endif
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
