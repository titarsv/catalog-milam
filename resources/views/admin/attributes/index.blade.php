@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Атрибуты
@endsection
@section('content')

    <h1>Список атрибутов</h1>

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

    <form action="/admin/attributes" method="post" id="settings-form">
        {!! csrf_field() !!}
        <div class="settings row">
            <div class="col-sm-12">
                <div class="input-group">
                    <label for="search" class="input-group-addon">Поиск:</label>
                    <input type="text" id="search" name="search" placeholder="Введите текст..." class="form-control input-sm" value="{{ $current_search }}" />
                </div>
            </div>
        </div>
    </form>

    <div class="panel-group">
        <div class="panel panel-default">
            @if($user->hasAccess(['attributes.create']))
            <div class="panel-heading text-right">
                <a href="javascript:void(0)" class="btn btn-primary" id="add_attribute">Добавить новый</a>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>Название</td>
                        <td>Українською</td>
                        <td>Значения</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($attributes as $attribute)
                        <tr>
                            <td>{{ $attribute->name }}</td>
                            <td>{{ $attribute->localize('ua', 'name') }}</td>
                            <td>
                                <ul class="nav">
                                    @forelse($attribute->values()->limit(10)->get() as $value)
                                        <li>{!! $value->name !!}</li>
                                    @empty
                                        <li>Нет добавленных значений!</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="actions" align="center">
                                @if($user->hasAccess(['attributes.view']))
                                <a class="btn btn-primary" href="/admin/attributes/edit/{!! $attribute->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                @endif
                                @if($user->hasAccess(['attributes.delete']))
                                <button type="button" class="btn btn-danger" onclick="confirmAttributesDelete('{!! $attribute->id !!}', '{{ $attribute->name }}')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" align="center">Нет добавленных атрибутов!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $attributes->links() }}
            </div>
        </div>
    </div>

    <div id="attributes-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить атрибут <span id="attribute-name"></span>?</p>
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
            $('#add_attribute').click(function(e){
                e.preventDefault();
                swal({
                    title: 'Введите название атрибута',
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
                            url:"/admin/attributes/create",
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
    </script>
@endsection
