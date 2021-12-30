@extends('layouts.master_clean')

@section('title','新增學生資料')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <form action="{{ route('school_admins.student_store') }}" method="post">
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
                                        $sc = \App\StudentClass::find($k);
                                    ?>
                                    <option value="{{ $k }}" @if($student_class->student_year == $sc->student_year and $student_class->student_class == $sc->student_class) selected @endif>{{ $v['年級'] }}年{{ $v['班級'] }}班--導師：{{ $v['導師'] }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="num" required>
                        </td>
                        <td>
                            <select class="form-control" name="sex">
                                <option value="男">男</option>
                                <option value="女">女</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="name" required>
                        </td>
                    </tr>
                </table>
                <br>
                <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">送出</button>
            </form>
        </div>
    </div>
@endsection
