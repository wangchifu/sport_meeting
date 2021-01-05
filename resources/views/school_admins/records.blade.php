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
                                        男子組：<br>
                                        <?php ksort($year_students[$k][$item->id]['男']); ?>
                                        @foreach($year_students[$k][$item->id]['男'] as $k1=>$v1)
                                            {{ $k1 }} {{ $v1 }},
                                        @endforeach
                                        <br>
                                        錄取：1、__________ 2、__________ 3、__________ 4、__________ 5、__________
                                        <br>
                                        女子組：<br>
                                        <?php ksort($year_students[$k][$item->id]['女']); ?>
                                        @foreach($year_students[$k][$item->id]['女'] as $k1=>$v1)
                                            {{ $k1 }} {{ $v1 }},
                                        @endforeach
                                        <br>
                                        錄取：1、__________ 2、__________ 3、__________ 4、__________ 5、__________
                                        <br>
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
