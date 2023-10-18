@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Событие
@endsection
@section('content')

    <h1>{{ $action->action_name }} сущности "{{ $action->entity_name }}" {{ $action->created_at }}</h1>

    <div class="form">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="table table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr class="success">
                            <td>Поле</td>
                            @if($action->action == 'update')
                                <td>До обновления</td>
                                <td>После обновления</td>
                            @else
                                <td>Значение</td>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($action->result as $field)
                            <tr>
                                <td>{{ $field['name'] }}</td>
                                @if($action->action == 'update')
                                    <td>{!! $field['old'] !!}</td>
                                    <td>{!! $field['new'] !!}</td>
                                @else
                                    <td>{!! $field['value'] !!}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table img{
            height: 50px;
        }
    </style>
@endsection
@include('admin.layouts.footer')

