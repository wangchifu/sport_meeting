@extends('layouts.master')

@section('title',$semester.'學期班級詳細資料')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">{{ $semester }}學期班級詳細資料</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('school_admins.api') }}">匯入學校 API</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $semester }}學期班級詳細資料</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <form name=myform>
                    <select class="form-control" name="student_class_id" onchange="jump()">
                        @foreach($class_data as $k=>$v)
                            <option value="{{ $k }}" @if($k == $select_class_id) selected @endif>{{ $v['年級'] }}年{{ $v['班級'] }}班--導師：{{ $v['導師'] }}</option>
                        @endforeach
                    </select>
                </form>
                <table class="table">
                    <thead class="table-primary">
                    <tr>
                        <th>
                            座號
                        </th>
                        <th>
                            性別
                        </th>
                        <th>
                            姓名
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>
                                {{ $student->num }}
                            </td>
                            <td>
                                {{ $student->sex }}
                            </td>
                            <td>
                                @if($student->sex == "男")
                                    <span class="text-primary">
                                @endif
                                @if($student->sex == "女")
                                    <span class="text-danger">
                                @endif
                                    {{ $student->name }}
                                    </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script language='JavaScript'>

        function jump(){
            if(document.myform.student_class_id.options[document.myform.student_class_id.selectedIndex].value!=''){
                location="/school_admins/{{ $semester }}/student_class/" + document.myform.student_class_id.options[document.myform.student_class_id.selectedIndex].value;
            }
        }
    </script>
@endsection
