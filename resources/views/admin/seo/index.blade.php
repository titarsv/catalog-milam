@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    SEO
@endsection
@section('content')

    <h1>SEO записи</h1>

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

    <form action="/admin/seo/list" method="get" id="settings-form">
        {!! csrf_field() !!}
        <div class="settings row">
            <div class="col-sm-4">

            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <label for="search" class="input-group-addon">Поиск:</label>
                    <input type="text" id="search" name="search" placeholder="Введите текст..." class="form-control input-sm" value="{{ !empty($current_search) ? $current_search : '' }}" />
                </div>
            </div>
            <div class="col-sm-4">

            </div>
        </div>
    </form>

    <div class="panel-group">
        <div class="panel panel-default">
            @if($user->hasAccess(['seo.create']))
            <div class="panel-heading text-right">
                <a href="/admin/seo/create" class="btn btn-primary">Добавить новую</a>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>URL</td>
                            <td>Название записи</td>
                            <td>Описание</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seo as $item)
                            <tr>
                                <td>{{ $item->url }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="description">
                                    <p>{{ $item->description }}</p>
                                </td>
                                <td class="actions" align="center">
                                    @if($user->hasAccess(['seo.view']))
                                    <a class="btn btn-primary" href="/admin/seo/edit/{!! $item->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    @endif
                                    @if($user->hasAccess(['seo.delete']))
                                    <button type="button" class="btn btn-danger" onclick="confirmSeoDelete('{!! $item->id !!}', '{!! $item->name !!}')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" align="center">Нет СЕО записей!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $seo->links() }}
            </div>
        </div>
    </div>

    <div id="seo-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить запись <span id="category-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

@endsection
