@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Статьи
@endsection
@section('content')
    <h1>Список статей</h1>
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
            <div class="panel-heading text-right">
                <span class="btn btn-primary" id="add_page">Создать статью</span>
            </div>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>ID</td>
                        <td>Заголовок</td>
                        <td>Изображение</td>
                        <td>Опубликовано</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>
                                <p>{{ $article->name }}</p>
                            </td>
                            <td>
                                @if(!empty($article->image_id) && !empty($article->image))
                                <img class="img-thumbnail" src="{{ $article->image->url() }}" alt="{!! $article->image->title !!}">
                                @endif
                            </td>
                            <td class="status">
                                <span class="{!! $article->status ? 'on' : 'off' !!}">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center" class="actions">
                                <a class="btn btn-primary" href="/admin/blog/edit/{!! $article->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a class="btn btn-danger" href="/admin/blog/delete/{!! $article->id !!}">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">Нет добавленных статей!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $articles->links() }}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#add_page').click(function(e){
                e.preventDefault();
                swal({
                    title: 'Введите название статьи',
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
                                url:"/admin/blog/create",
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
            $('#html-delete-modal #confirm').attr('href', '/admin/blog/delete/' + id);
            $('#html-delete-modal #html-name').html(name);
            $('#html-delete-modal').modal();
        }
    </script>
@endsection
