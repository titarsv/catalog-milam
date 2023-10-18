@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Наши работы
@endsection
@section('content')
    <h1>Список наших работ</h1>
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
            @if($user->hasAccess(['news.create']))
            <div class="panel-heading text-right">
                <span class="btn btn-primary" id="add_page">Добавить нашу работу</span>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>ID</td>
                        <td>Название</td>
                        <td>Изображение</td>
                        <td>URL</td>
                        <td>Опубликовано</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($works as $work)
                        <tr>
                            <td>{{ $work->id }}</td>
                            <td>
                                <p>{{ $work->name }}</p>
                            </td>
                            <td>
                                @if(!empty($work->image_id) && !empty($work->image))
                                <img class="img-thumbnail" src="{{ $work->image->url() }}" alt="{!! $work->image->title !!}">
                                @endif
                            </td>
                            <td>
                                <p>{{ $work->link() }}</p>
                            </td>
                            <td class="status">
                                <span class="{!! $work->visible ? 'on' : 'off' !!}">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center" class="actions">
                                @if($user->hasAccess(['works.view']))
                                <a class="btn btn-primary" href="/admin/works/edit/{!! $work->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                @endif
                                @if($user->hasAccess(['works.delete']))
                                <a class="btn btn-danger" onclick="confirmDelete('{!! $work->id !!}', '{!! $work->name !!}')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">Нет добавленных работ!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $works->links() }}
            </div>
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
                    <p>Удалить работу <span id="name"></span>?</p>
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
                    title: 'Введите название выполненной работы',
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
                                url:"/admin/works/create",
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
        function confirmDelete(id, name) {
            $('#delete-modal #confirm').attr('href', '/admin/works/delete/' + id);
            $('#delete-modal #name').html(name);
            $('#delete-modal').modal();
        }
    </script>
@endsection
