@extends('layouts.master')

@section('title',$schools[$school_code].'歷次成績')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">{{ $schools[$school_code] }} 歷次成績</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <form name="myform" method="post" id="myform">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                        </div>
                        <div class="col-4">
                            <a href="{{ route('all') }}" class="btn btn-secondary">返回</a>
                        </div>
                    </div>
                    <input type="hidden" name="school_code" value="{{ $school_code }}">
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
                        <?php
                        $check_signs = \App\StudentSign::where('action_id',$select_action)
                            ->where('student_year',$student_class->student_year)
                            ->where('student_class',$student_class->student_class)
                            ->count();
                        ?>
                        @if($check_signs > 0)
                            <tr>
                                <td>
                                    {{ $student_class->student_year }}年級
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
                                            <?php
                                            if($student_sign->sex == "男") $color="text-primary";
                                            if($student_sign->sex == "女") $color = "text-danger";
                                            if($student_sign->sex == "4") $color = "text-info";
                                            ?>
                                            <span class="{{ $color }}">
                                                @if($item->game_type == "personal")
                                                    {{ mb_substr($student_sign->student->name,0,1) }}◯
                                                @endif
                                                @if($item->game_type == "group")
                                                    {{ mb_substr($student_sign->student->name,0,1) }}◯
                                                @endif
                                                @if($item->game_type == "class")
                                                    全班
                                                @endif
                                                @if($student_sign->achievement)
                                                    [{{ $student_sign->achievement }} {{ $rankings[$student_sign->ranking] }}]
                                                @endif
                                            </span>
                                            <br>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function jump(){
            $('#myform').submit();
        }
    </script>
@endsection
