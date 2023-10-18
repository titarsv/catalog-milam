@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Наши видеогаллереи
@endsection
@section('content')
    <h1>Список наших видеогаллерей</h1>
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
                <span class="btn btn-primary" id="add_page">Добавить видеогаллерею</span>
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

                    @forelse($galleries as $gallery)
                        <tr>
                            <td>{{ $gallery->id }}</td>
                            <td>
                                <p>{{ $gallery->name }}</p>
                            </td>
                            <td>
                                @if(!empty($gallery->image_id) && !empty($gallery->image))
                                <img class="img-thumbnail" src="{{ $gallery->image->url() }}" alt="{!! $gallery->image->title !!}">
                                @endif
                            </td>
                            <td>
                                <p>{{ $gallery->link() }}</p>
                            </td>
                            <td class="status">
                                <span class="{!! $gallery->visible ? 'on' : 'off' !!}">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center" class="actions">
                                @if($user->hasAccess(['photos.view']))
                                <a class="btn btn-primary" href="/admin/videos/edit/{!! $gallery->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                @endif
                                @if($user->hasAccess(['photos.delete']))
                                <a class="btn btn-danger" onclick="confirmDelete('{!! $gallery->id !!}', '{!! $gallery->name !!}')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">Нет добавленных видеогаллерей!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $galleries->links() }}
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
                    <p>Удалить видеогаллерею <span id="name"></span>?</p>
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
                    title: 'Введите название галлереи',
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
                                url:"/admin/videos/create",
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
            $('#delete-modal #confirm').attr('href', '/admin/videos/delete/' + id);
            $('#delete-modal #name').html(name);
            $('#delete-modal').modal();
        }
    </script>
@endsection
