@extends('layouts.master')

@section('title','比賽項目')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">比賽項目</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <?php
                        $action = \App\Action::find($select_action);
                    ?>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('school_admins.action') }}">「報名任務」列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $action->name }}</li>
                    </ol>
                </nav>
                <form name="myform">
                <div class="form-row">
                    <!--
                    <div class="form-group col-md-6">
                        {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                    </div>
                    -->
                    <div class="form-group col-md-12">
                        <a class="btn btn-success" href="{{ route('school_admins.item_create',$select_action) }}">新增比賽項目</a>
                    </div>
                </div>
                    <input type="hidden" name="select_action" value="{{ $select_action }}">
                </form>
                <form action="{{ route('school_admins.item_import') }}" method="post">
                    @csrf
                    或是 從
                    <select name="old_action_id">
                        @foreach($actions as $action)
                            @if($action->id <> $select_action)
                                <option value="{{ $action->id }}">{{ $action->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="hidden" name="new_action_id" value="{{ $select_action }}">
                    <button onclick="return confirm('確定嗎？')"><i class="fas fa-copy"></i> 複製至此</button>
                </form>
                <table class="table table-striped">
                    <thead class="table-primary">
                    <tr>
                        <th>
                            排序
                        </th>
                        <th>
                            名稱
                        </th>
                        <th>
                            組別
                        </th>
                        <th>
                            類別
                        </th>
                        <th>
                            參賽年級
                        </th>
                        <th>
                            動作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                {{ $item->order }}
                            </td>
                            <td>
                                @if($item->limit)
                                    <span class="badge badge-danger">限</span>
                                @endif
                                <span @if($item->disable) style="text-decoration:line-through" @endif>
                                    {{ $item->name }}
                                </span>
                                @if($item->game_type=="personal")
                                    <span class="badge badge-warning">個人</span>
                                @endif
                                @if($item->game_type=="group")
                                    <span class="badge badge-primary">團體</span>({{ $item->official }};{{ $item->reserve }})
                                @endif
                                @if($item->game_type=="class")
                                    <span class="badge badge-info">班際</span>
                                @endif
                                @if($item->disable)
                                    <span class="text-danger">[停用]</span>
                                @endif
                            </td>
                            <td>
                                <span @if($item->disable) style="text-decoration:line-through" @endif>
                                    @if($item->group == 1)
                                        <span class="text-primary">男子組</span>
                                    @elseif($item->group == 2)
                                        <span class="text-danger">女子組</span>
                                    @elseif($item->group == 3)
                                        <span class="text-primary">男子組</span>+<span class="text-danger">女子組</span> 各
                                    @endif
                                    {{ $item->people }} 個(隊) 取 {{ $item->reward }} 名
                                </span>
                            </td>
                            <td>
                                <span @if($item->disable) style="text-decoration:line-through" @endif>
                                    @if($item->type == 1)
                                        徑賽
                                    @elseif($item->type == 2)
                                        田賽
                                    @elseif($item->type == 3)
                                        其他
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span @if($item->disable) style="text-decoration:line-through" @endif>
                                @foreach(unserialize($item->years) as $y)
                                    {{ $y }},
                                @endforeach
                                </span>
                            </td>
                            <td>
                                @if($item->disable)
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#itemModal" data-whatever="{{ route('school_admins.item_enable',$item->id) }}" data-name="{{ $item->name }}" data-act="enable">啟用</button>
                                @else
                                    <a class="btn btn-primary btn-sm" href="{{ route('school_admins.item_edit',$item->id) }}">修改</a>
                                    <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#itemModal" data-whatever="{{ route('school_admins.item_delete',$item->id) }}" data-name="{{ $item->name }}" data-act="delete">停用</button>
                                @endif
                                <a href="{{ route('school_admins.item_destroy',$item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('連同學生報名資料、成績、名次一起刪除喔')">刪除</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">請確認</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="showText"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">按錯了</button>
                    <a href="" id="do" class="btn btn-primary">確定</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function jump(){
            if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
                location="/school_admins/item/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }

        $(function () { $('#itemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever')
            var act = button.data('act')
            var name = button.data('name')
            $('#do').attr("href", recipient);
            if(act == "delete"){
                $('#showText').text('停用 ['+name+'] ？');
            }
            if(act == "enable"){
                $('#showText').text('啟用 ['+name+'] ？');
            }

        })
        });

    </script>
@endsection
