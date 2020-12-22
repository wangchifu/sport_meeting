@extends('layouts.master')

@section('title','報名比賽')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">報名比賽</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <table class="table table-striped">
                    <thead class="table-primary">
                    <tr>
                        <th>
                            序號
                        </th>
                        <th>
                            名稱
                        </th>
                        <th>
                            創建時間
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
                                {{ $i }}
                            </td>
                            <td>
                                {{ $action->name }}
                            </td>
                            <td>
                                {{ $action->created_at }}
                            </td>
                            <td>
                                <?php
                                    $check = \App\StudentSign::where('action_id',$action->id)->where('student_year',$student_year)->where('student_class',$student_class)->get();
                                ?>
                                @if(count($check) === 0)
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
