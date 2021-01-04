@extends('layouts.master')

@section('title','歷次成績')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">歷次成績</h1>
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
                        <td>

                        </td>
                        @foreach($items as $item)
                            <td>
                                {{ $item->name }}
                            </td>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($student_classes as $student_class)
                        <tr>
                            <td>
                                {{ $student_class->student_year }}年{{ $student_class->student_class }}班
                            </td>
                            @foreach($items as $item)
                                <?php
                                $years_array = unserialize($item->years);
                                $student_signs = \App\StudentSign::where('item_id',$item->id)
                                    ->where('student_year',$student_class->student_year)
                                    ->where('student_class',$student_class->student_class)
                                    ->orderBy('sex','DESC')
                                    ->get();
                                $rankings = config('chcschool.rankings');
                                ?>
                                <td>
                                    @if(in_array($student_class->student_year,$years_array) and count($student_signs)==0)
                                        未報名
                                    @endif
                                    @if(!in_array($student_class->student_year,$years_array))
                                        --
                                    @endif
                                    @foreach($student_signs as $student_sign)
                                        @if($student_sign->student->sex == "男")
                                            <span class="text-primary">
                                            {{ $student_sign->student->name }}
                                                @if($student_sign->achievement)
                                                    [{{ $student_sign->achievement }}{{ $rankings[$student_sign->ranking] }}]
                                                @endif
                                        </span>
                                            <br>
                                        @endif
                                        @if($student_sign->student->sex == "女")
                                            <span class="text-danger">
                                            {{ $student_sign->student->name }}
                                                @if($student_sign->achievement)
                                                    [{{ $student_sign->achievement }}{{ $rankings[$student_sign->ranking] }}]
                                                @endif
                                        </span>
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                            @endforeach
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
                location="/show/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
