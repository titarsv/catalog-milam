@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Категории
@endsection
@section('content')

    <h1>Список категорий</h1>

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
            @if($user->hasAccess(['categories.create']))
            <div class="panel-heading text-right">
                <a href="/admin/categories/create" class="btn btn-primary">Добавить новую</a>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название категории</td>
                            <td>Українською</td>
                            <td>English</td>
                            <td>Порядок сортировки</td>
                            <td>Статус</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td><a href="{{ $category->link() }}" target="_blank">{{ $category->name }}</a></td>
                                <td>{{ $category->localize('ua', 'name') }}</td>
                                <td>{{ $category->localize('en', 'name') }}</td>
                                <td>{{ $category->sort_order }}</td>
                                <td class="status">
                                    <span class="{!! $category->status ? 'on' : 'off' !!}">
                                        <span class="runner"></span>
                                    </span>
                                </td>
                                <td class="actions" align="center">
                                    @if($user->hasAccess(['categories.view']))
                                    <a class="btn btn-primary" href="/admin/categories/edit/{!! $category->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    @endif
                                    @if($user->hasAccess(['categories.delete']) && $category->id > 1)
                                        <button type="button" class="btn btn-danger" onclick="confirmCategoriesDelete('{{ $category->id }}', '{{ $category->name }}')">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" align="center">Нет добавленных категорий!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <div id="categories-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить категорию <span id="category-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

@endsection
