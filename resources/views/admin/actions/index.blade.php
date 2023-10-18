@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    События
@endsection
@section('content')

    <h1>События</h1>

    <div class="panel-group">
        <div class="panel panel-default">
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Тип события</td>
                            <td>Тип объекта</td>
                            <td>Инициатор</td>
                            <td>Дата</td>
                            <td align="center">Подробнее</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($actions as $item)
                            <tr>
                                <td>{{ $item->action_name }}</td>
                                <td>{{ $item->entity_name }}</td>
                                <td>{{ $item->user->first_name }} {{ $item->user->last_name }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td class="actions" align="center">
                                    @if($user->hasAccess(['actions.view']))
                                    <a class="btn btn-primary" href="/admin/actions/show/{!! $item->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" align="center">Нет событий!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $actions->links() }}
            </div>
        </div>
    </div>
@endsection
