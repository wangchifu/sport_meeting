@extends('layouts.master')

@section('title','成績登錄')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">成績登錄</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <form name="myform">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                        </div>
                    </div>
                </form>
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
                            年級
                        </th>
                        <th>
                            組別
                        </th>
                        <th>
                            動作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                    <tr>
                        <form action="{{ route('school_scores.score_input_do') }}" method="post">
                            @csrf
                        <td>
                            {{ $item->order }}
                        </td>
                        <td>
                            {{ $item->name }}
                            @if($item->game_type=="personal")
                                <span class="badge badge-warning">個人賽</span>
                            @endif
                            @if($item->game_type=="group")
                                <span class="badge badge-primary">團體賽</span>
                            @endif
                            @if($item->game_type=="class")
                                <span class="badge badge-info">班際賽</span>
                            @endif
                        </td>
                        <td>
                            <?php
                                $years = unserialize($item->years);
                            ?>
                            <select class="form-control" name="student_year">
                            @foreach($years as  $v)
                                <option value="{{ $v }}">
                                    {{ $v }} 年級
                                </option>
                            @endforeach
                            </select>
                        </td>
                        <td>
                            <select id="sex" class="form-control" name="sex">
                                @if($item->group ==1 or $item->group ==3)
                                    <option value="男">男子組</option>
                                @endif
                                @if($item->group ==1 or $item->group ==3)
                                        <option value="女">女子組</option>
                                @endif
                                @if($item->group ==4)
                                    <option value="4">不分性別</option>
                                @endif
                            </select>
                        </td>
                            <input type="hidden" name="action_id" value="{{ $select_action }}">
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <td>
                            <button class="btn btn-primary btn-sm">填寫</button>
                        </td>
                        </form>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function jump(){
            if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
                location="/school_scores/score_input/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
