@extends('layouts.master')

@section('title','項目記錄表')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">項目記錄表</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <form name="myform">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                        </div>
                    </div>
                </form>
                @include('layouts.errors')
                <table class="table table-striped">
                    <thead class="table-primary">
                    <tr>
                        <th>
                            <a href="{{ route('school_admins.action_set_number',$action->id) }}" class="btn btn-outline-primary btn-sm" onclick="return confirm('確定？')">學生編入布牌號碼</a> <a href="{{ route('school_admins.action_set_number_null',$action->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('確定清空？')">學生布牌號碼清空</a>
                            資料 <a href="{{ route('school_admins.download_records',$action->id) }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> 下載</a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php $cht_num = config('chcschool.cht_num'); ?>
                        @foreach($years as $k=>$v)
                            <h3>
                                {{ $cht_num[$k] }}年級
                            </h3>
                            <?php $n=1; ?>
                            @foreach($items as $item)
                                <?php
                                 $years_array = unserialize($item->years);
                                ?>
                                @if(in_array($k,$years_array))
                                    <h4>
                                        ({{ $n }}) {{ $item->name }}
                                    </h4>
                                    <?php
                                        $n++;
                                    ?>
                                    @if(isset($year_students[$k][$item->id]))
                                        @if(isset($year_students[$k][$item->id]['男']))
                                            男子組：<br>
                                            <?php ksort($year_students[$k][$item->id]['男']); ?>
                                            @foreach($year_students[$k][$item->id]['男'] as $k1=>$v1)
                                                {{ $st_number[$k1] }} {{ $v1 }},
                                            @endforeach
                                            <br>
                                            錄取：
                                                @for($i=1;$i<=$item->reward;$i++)
                                                    {{ $i }}、__________
                                                @endfor
                                            <br>
                                        @endif
                                        @if(isset($year_students[$k][$item->id]['女']))
                                            女子組：<br>
                                            <?php ksort($year_students[$k][$item->id]['女']); ?>
                                            @foreach($year_students[$k][$item->id]['女'] as $k1=>$v1)
                                                {{ $st_number[$k1] }} {{ $v1 }},
                                            @endforeach
                                            <br>
                                            錄取：
                                                @for($i=1;$i<=$item->reward;$i++)
                                                    {{ $i }}、__________
                                                @endfor
                                            <br>
                                        @endif
                                        @if(isset($year_students[$k][$item->id][4]))
                                            @foreach($year_students[$k][$item->id][4] as $k1=>$v1)
                                                {{ $v1 }},
                                            @endforeach
                                            <br>
                                            錄取：
                                            @for($i=1;$i<=$item->reward;$i++)
                                                {{ $i }}、__________
                                            @endfor
                                            <br>
                                        @endif
                                    @endif
                                    <br>
                                @endif
                            @endforeach
                            <br><br>
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
                location="/school_admins/records/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
