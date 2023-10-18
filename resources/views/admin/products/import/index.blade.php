@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Каталог импортов
@endsection
@section('content')

    <h1>Каталог импортов</h1>

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
            @if($user->hasAccess(['import.create']))
            <div class="panel-heading text-right">
                <button id="add_import" class="btn btn-primary">Добавить новый</button>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название импорта</td>
                            <td>Дата импорта</td>
                            <td>Импорт запланирован на</td>
                            <td>Статус загрузки</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($imports as $import)
                            <tr>
                                <td>{{ $import->name }}</td>
                                <td>{{ isset($import->schedule->updated_at) ? date('Y-m-d H:i:s', $import->schedule->updated_at) : 'не импортировано' }}</td>
                                <td>
                                    @if(isset($import->schedule->status) && $import->schedule->status != 1)
                                        происходит сейчас
                                    @else
                                        {{ isset($import->schedule->nextRun) ? date('Y-m-d H:i:s', $import->schedule->nextRun) : 'не запланировано' }}
                                    @endif
                                </td>
                                <td>{{ isset($import->status) ? round($import->status).'%' : '-' }}</td>
                                <td class="actions" align="center" style="width: 180px;">
                                    @if($user->hasAccess(['import.view']))
                                    <a class="btn btn-primary" href="/admin/products/import/edit/{!! $import->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    @endif
                                    @if($user->hasAccess(['import.delete']))
                                    <button type="button" class="btn btn-danger" onclick="confirmImportDelete('{!! $import->id !!}', '{!! $import->name !!}')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" align="center">Нет записей импорта!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{--<div class="panel-footer text-right">--}}
                {{--{{ $imports->links() }}--}}
            {{--</div>--}}
        </div>
    </div>

    <div id="import-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить запись <span id="import-name"></span>?</p>
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
            $('#add_import').click(function(){
                swal({
                    title: 'Новый импорт',
                    html:
                    '<div class="swal2-content">' +
                    '<div class="form-group">' +
                    '<label for="import_file">Файл с данными для импорта:</label>' +
                    '<input id="import_file" name="import_file" type="file" accept=".xlsx,.xls,.csv" aria-label="Файл импорта" class="swal2-file" style="display: flex;" placeholder="Файл импорта">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="attachments">Архив фотографий:</label>' +
                    '<input id="attachments" name="attachments" type="file" accept=".zip" aria-label="Вложения" class="swal2-file" style="display: flex;" placeholder="Вложения">' +
                    '</div>' +
                    '</div>',
                    focusConfirm: false,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            let formData = new FormData();
                            let import_file = $('#import_file').get(0);
                            formData.append('import_file', import_file.files[0]);
                            let attachments = $('#attachments').get(0);
                            formData.append('attachments', attachments.files[0]);
                            $.ajax({
                                type:"POST",
                                url:"/admin/products/import/upload",
                                data: formData,
                                processData: false,
                                contentType: false,
                                async:true,
                                success: function(response){
                                    if(response.result === 'success'){
                                        resolve(response.redirect);
                                    }else{
                                        reject(response.errors);
                                    }
                                }
                            });
                        });
                    }
                }).then(function(redirect) {
                    location = redirect;
                }, function(errors) {
                    if(typeof errors !== 'string'){
                        var message = '';
                        for(err in errors){
                            message += errors[err] + '<br>';
                        }
                        swal(
                            'Ошибка!',
                            message,
                            'error'
                        );
                    }
                });
            });
        });

        function confirmImportDelete(id, name) {
            $('#import-delete-modal #confirm').attr('href', '/admin/products/import/delete/' + id);
            $('#import-delete-modal #import-name').html(name);
            $('#import-delete-modal').modal();
        }
    </script>
@endsection
