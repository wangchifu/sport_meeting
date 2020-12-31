@extends('layouts.master')

@section('title','成績登入')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">成績登入</h1>
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
                            動作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            {{ $item->order }}
                        </td>
                        <td>
                            {{ $item->name }}
                        </td>
                        <td>
                            <a href="{{ route('school_scores.score_input_do',['action'=>$action->id,'item'=>$item->id]) }}" class="btn btn-primary btn-sm">填寫</a>
                        </td>
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
