@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Страницы
@endsection
@section('content')

    <h1>Список страниц</h1>

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
            @if($user->hasAccess(['pages.create']))
            <div class="panel-heading text-right">
                <span class="btn btn-primary" id="add_page">Создать страницу</span>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>Название</td>
                        <td>Порядок сортировки</td>
                        <td>Статус</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($content as $page)
                        <tr>
                            <td><a href="{{ $page->link() }}" target="_blank">{{ $page->name }}</a></td>
                            <td>{{ $page->sort_order }}</td>
                            <td class="status">
                                    <span class="{!! $page->status ? 'on' : 'off' !!}">
                                        <span class="runner"></span>
                                    </span>
                            </td>
                            <td class="actions" align="center">
                                @if($user->hasAccess(['pages.view']))
                                <a class="btn btn-primary" href="/admin/pages/edit/{!! $page->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                @endif
                                @if($user->hasAccess(['pages.delete']))
                                <button type="button" class="btn btn-danger" onclick="confirmPageDelete('{!! $page->id !!}', '{!! $page->name !!}')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">На сайте нет страниц!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $content->links() }}
            </div>
        </div>
    </div>

    <div id="html-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить страницу <span id="html-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#add_page').click(function(e){
                e.preventDefault();
                swal({
                    title: 'Введите название страницы',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    focusConfirm: false,
                    preConfirm: (name) => {
                        return new Promise((resolve, reject) => {
                            let formData = new FormData();
                            formData.append('name_{{ Config::get('app.locale') }}', name);
                            $.ajax({
                                type:"POST",
                                url:"/admin/pages/create",
                                data: formData,
                                processData: false,
                                contentType: false,
                                async:true,
                                success: function(response){
                                    console.log(response);
                                    if(response.result === 'success'){
                                        resolve(response.redirect);
                                    }else{
                                        reject(response.errors);
                                    }
                                }
                            });
                        })
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
        function confirmPageDelete(id, name) {
            $('#html-delete-modal #confirm').attr('href', '/admin/pages/delete/' + id);
            $('#html-delete-modal #html-name').html(name);
            $('#html-delete-modal').modal();
        }
    </script>
@endsection
