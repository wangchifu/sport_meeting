@extends('layouts.master')

@section('title','比賽項目')

@section('main')
    <div class="container-fluid">
        <h1 class="mt-4">比賽項目</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <a class="btn btn-success" href="{{ route('school_admins.item_create') }}">新增比賽項目</a>
                <table class="table table-hover">
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
                                        <span class="text-primary">男子組</span>+<span class="text-danger">女子組</span>
                                    @endif
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
                                @foreach(unserialize($item->years) as $y)
                                    {{ $y }},
                                @endforeach
                            </td>
                            <td>
                                @if($item->disable)
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#itemModal" data-whatever="{{ route('school_admins.item_enable',$item->id) }}" data-name="{{ $item->name }}" data-act="enable">啟用</button>
                                @else
                                    <a class="btn btn-primary btn-sm" href="{{ route('school_admins.item_edit',$item->id) }}">修改</a>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#itemModal" data-whatever="{{ route('school_admins.item_delete',$item->id) }}" data-name="{{ $item->name }}" data-act="delete">停用</button>
                                @endif
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
