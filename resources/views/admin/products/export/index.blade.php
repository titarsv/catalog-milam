@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Каталог экспортов
@endsection
@section('content')

    <h1>Каталог экспортов</h1>

    @if (session('message-success'))
        <div class="alert alert-success">
            {{ session('message-success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(session('message-error'))
        <div class="alert alert-danger">
            {{ session('message-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="panel-group">
        <div class="panel panel-default">
            @if($user->hasAccess(['export.create']))
            <div class="panel-heading text-right">
                <a href="/admin/products/export/create" class="btn btn-primary">Добавить новый</a>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название экспорта</td>
                            <td>Тип</td>
                            <td>URL</td>
                            <td>Последнее обновление</td>
                            <td>Следующее обновление</td>
                            <td>Статус генерации</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exports as $export)
                            <tr>
                                <td>{{ $export->name }}</td>
                                <td>{{ $export->type }}</td>
                                <td>
                                    @if(is_file(public_path('exports/'.$export->url.'.'.$export->type)))
                                        <a href="{{env('APP_URL')}}/exports/{{ $export->url.'.'.$export->type }}"{{ $export->type == 'rss' ? ' target="_blank"' : '' }}>{{ $export->url.'.'.$export->type }}</a>
                                    @else
                                        {{ $export->url.'.'.$export->type }}
                                    @endif
                                </td>
                                <td>{{ isset($export->schedule->updated_at) ? date('Y-m-d H:i:s', $export->schedule->updated_at) : 'никогда' }}</td>
                                <td>
                                    @if(isset($export->schedule->status) && $export->schedule->status != 1)
                                        происходит сейчас
                                    @else
                                        {{ isset($export->schedule->nextRun) ? date('Y-m-d H:i:s', $export->schedule->nextRun) : 'не запланировано' }}
                                    @endif
                                </td>
                                <td>{{ isset($export->schedule->status) ? round($export->schedule->status*100).'%' : '-' }}</td>
                                <td class="actions" align="center" style="width: 240px;">
                                    @if($user->hasAccess(['import.view']))
                                    <a class="btn btn-warning fast_export" href="javascript:void(0);" data-id="{{ $export->id }}">
                                        <i class="glyphicon glyphicon-refresh"></i>
                                    </a>
                                    @if(is_file(public_path('exports/'.$export->url.'.'.$export->type)))
                                        <a class="btn btn-success" href="{{env('APP_URL')}}/exports/{{ $export->url.'.'.$export->type }}" download>
                                            <i class="glyphicon glyphicon-floppy-save"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-success" href="/admin/products/export/download/{!! $export->id !!}" data-toggle="tooltip" data-placement="top" title="Скачать">
                                            <i class="glyphicon glyphicon-floppy-save"></i>
                                        </a>
                                    @endif
                                    <a class="btn btn-primary" href="/admin/products/export/edit/{!! $export->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    @endif
                                    @if($user->hasAccess(['import.delete']))
                                    <button type="button" class="btn btn-danger" onclick="confirmExportDelete('{!! $export->id !!}', '{!! $export->name !!}')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" align="center">Нет записей экспорта!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{--<div class="panel-footer text-right">--}}
                {{--{{ $exports->links() }}--}}
            {{--</div>--}}
        </div>
    </div>

    <div id="delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить запись <span id="delete-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function(){
            $('.fast_export').click(function(e){
                e.preventDefault();
                var id = $(this).data('id');
                swal({
                    title: 'Происходит генерация файла.',
                    html: '<p>Ожидайте окончания процесса.</p><div id="export_progress"></div>',
                    onBeforeOpen: () => {
                        swal.showLoading();
                    }
                });
                next_export_step(id, 1);
            });
            function next_export_step(id, start = 0){
                $.post("/admin/products/export/refresh/"+id, {start: start}, function(response){
                    console.log(response);
                    if(response.saved != response.total) {
                        let percent = Math.round(response.saved / response.total * 100);
                        $('#export_progress').html('<p>Обработано ' + response.saved + ' из ' + response.total + ' товаров</p>' +
                            '<div class="progress progress-striped active">\n' +
                            '<div class="progress-bar"  role="progressbar" aria-valuenow="' + percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + percent + '%">\n' +
                            percent + '%\n' +
                            '</div>\n' +
                            '</div>');
                        next_export_step(id);
                    }else{
                        $('#export_progress').html('<p>Экспорт окончен!</p>' +
                            '<div class="progress progress-striped active">\n' +
                            '<div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">\n' +
                            '100%\n' +
                            '</div>\n' +
                            '</div>');
                        swal({
                            type: 'success',
                            title: 'Экспорт окончен!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
        });

        function confirmExportDelete(id, name){
            $('#delete-modal #confirm').attr('href', '/admin/products/export/delete/' + id);
            $('#delete-modal #delete-name').html(name);
            $('#delete-modal').modal();
        }
    </script>
@endsection
