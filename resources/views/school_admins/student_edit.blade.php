@extends('layouts.master_clean')

@section('title','學生資料')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <form action="{{ route('school_admins.student_update',$student->id) }}" method="post">
                @csrf
                <table>
                    <tr>
                        <td>
                            班級
                        </td>
                        <td>
                            座號
                        </td>
                        <td>
                            性別
                        </td>
                        <td>
                            姓名
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control" name="student_class_id">
                                @foreach($class_data as $k=>$v)
                                    <?php
                                        $student_class = \App\StudentClass::find($k);
                                    ?>
                                    <option value="{{ $k }}" @if($student_class->student_year == $student->student_year and $student_class->student_class == $student->student_class) selected @endif>{{ $v['年級'] }}年{{ $v['班級'] }}班--導師：{{ $v['導師'] }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="num" value="{{ $student->num }}" required>
                        </td>
                        <td>
                            <?php
                            $s1 = ($student->sex == "男")?"selected":null;
                            $s2 = ($student->sex == "女")?"selected":null;
                            ?>
                            <select class="form-control" name="sex">
                                <option value="男" {{ $s1 }}>男</option>
                                <option value="女" {{ $s2 }}>女</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="name" value="{{ $student->name }}" required>
                        </td>
                    </tr>
                </table>
                <br>
                <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">送出</button>
            </form>
        </div>
    </div>
@endsection
