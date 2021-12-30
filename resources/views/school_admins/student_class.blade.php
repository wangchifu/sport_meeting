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
                <a href="javascript:edit_student('{{ route('school_admins.student_create',$select_class_id) }}','新視窗')" class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> 新增學生</a>
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
                        @if($student->disable == null)
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
                                    {{ $student->name }} <a href="{{ route('school_admins.student_disable',$student->id) }}" onclick="return confirm('停用這位學生？')"><i class="fas fa-times-circle text-danger"></i></a>
                                        <a href="javascript:edit_student('{{ route('school_admins.student_edit',$student->id) }}','新視窗')"><i class="fas fa-edit text-primary"></i></a>
                                    </span>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                    <tfoot class="bg-light" style="text-decoration:line-through">
                    @foreach($students as $student)
                        @if($student->disable == 1)
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
                                        {{ $student->name }} <a href="{{ route('school_admins.student_disable',$student->id) }}" onclick="return confirm('再啟用這位學生？')"><i class="fas fa-check-circle text-success"></i></a>
                                        </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tfoot>
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

        function edit_student(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=200');
        }
    </script>
@endsection
