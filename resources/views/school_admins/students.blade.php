@extends('layouts.master')

@section('title','註冊選手名單')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">註冊選手名單</h1>
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
                        @foreach($student_classes as $student_class)
                            <?php
                                $user_ids =  explode(',',$student_class->user_ids);
                                $user_name = null;
                                foreach($user_ids as $user_id){
                                    $user = \App\User::find($user_id);
                                    $user_name .= $user->name.'　';
                                }

                                $cht_num = config('chcschool.cht_num');

                            ?>
                                <span style="font-weight:bold;">{{ $cht_num[$student_class->student_year] }}年{{ $student_class->student_class }}班　　　　領隊：{{ $user_name }}</span><br>
                            <?php
                                $select_students['男'] = [];
                                $select_students['女'] = [];
                                $student_signs = \App\StudentSign::where('action_id',$select_action)
                                    ->where('student_year',$student_class->student_year)
                                    ->where('student_class',$student_class->student_class)
                                    ->get();
                                foreach($student_signs as $student_sign){
                                    $select_students[$student_sign->sex][$student_sign->student_id] = $student_sign->student->name;
                                }

                                $numbers = $action->numbers;

                            ?>
                            男子組：<br>
                                <?php
                                    $s = 1;
                                ?>
                                @foreach($select_students['男'] as $k=>$v)
                                    <?php
                                    if($numbers == 4){
                                        $number = $student_class->student_year.$student_class->student_class.sprintf("%02s",$s);
                                    }
                                    if($numbers == 5){
                                        $number = $student_class->student_year.sprintf("%02s",$student_class->student_class).sprintf("%02s",$s);
                                    }
                                    $student = \App\Student::find($k);
                                    $att['number'] = $number;
                                    $student->update($att);
                                    ?>
                                    {{ $number }} {{ $v }},
                                    <?php $s++; ?>
                                @endforeach
                                <br>
                            女子組：<br>
                                @foreach($select_students['女'] as $k=>$v)
                                    <?php
                                    if($numbers == 4){
                                        $number = $student_class->student_year.$student_class->student_class.sprintf("%02s",$s);
                                    }
                                    if($numbers == 5){
                                        $number = $student_class->student_year.sprintf("%02s",$student_class->student_class).sprintf("%02s",$s);
                                    }
                                    $student = \App\Student::find($k);
                                    $att['number'] = $number;
                                    $student->update($att);
                                    ?>
                                    {{ $number }} {{ $v }},
                                    <?php $s++; ?>
                                @endforeach
                                <br>
                            <br>
                        @endforeach
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
                location="/school_admins/students/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
