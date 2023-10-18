@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Акции
@endsection
@section('content')

    <h1>Список акций</h1>

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
            @if($user->hasAccess(['sales.create']))
            <div class="panel-heading text-right">
                <a href="/admin/sales/create" class="btn btn-primary">Добавить новую</a>
            </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название акции</td>
                            <td>Описание</td>
                            <td>Период действия</td>
                            <td>Статус</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->name }}</td>
                                <td class="description">
                                    {!! $sale->body !!}
                                </td>
                                <td class="description">
                                    {{ $sale->show_from }} - {{ $sale->show_to }}
                                </td>
                                <td>
                                    <span class="{!! $sale->status ? 'on' : 'off' !!}">
                                        <span class="runner"></span>
                                    </span>
                                </td>
                                <td class="actions" align="center">
                                    @if($user->hasAccess(['sales.view']))
                                    <a class="btn btn-primary" href="/admin/sales/edit/{!! $sale->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    @endif
                                    @if($user->hasAccess(['sales.delete']))
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $sale->id }}', '{{ $sale->name }}')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" align="center">Нет акционных предложений!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $sales->links() }}
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
                    <p>Удалить акцию <span id="delete-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm-delete">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            $('#confirm-delete').attr('href', '/admin/sales/delete/' + id);
            $('#delete-name').html(name);
            $('#delete-modal').modal();
        }
    </script>
@endsection