@extends('layouts.master')

@section('title','成績記錄單')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">成績記錄單</h1>
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
                            資料
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            施工中
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function jump(){
            if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
                location="/school_admins/scores/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
