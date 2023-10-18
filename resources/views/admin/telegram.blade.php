@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Настройки
@endsection
@section('content')

    <h1>Telegram</h1>

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

    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки Telegram</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Токен</label>
                                <div class="form-element col-sm-10">
                                    @if(old('token') !== null)
                                        <input type="text" class="form-control" name="meta_title" value="{!! old('token') !!}" />
                                        @if($errors->has('token'))
                                            <p class="warning" role="alert">{!! $errors->first('token',':message') !!}</p>
                                        @endif
                                    @else
                                        <input type="text" class="form-control" name="token" value="{{ !empty($telegram['token']) ? $telegram['token'] : '' }}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Подписчики</h4>
                    </div>
                    <div class="panel-body">
                        @if(!empty($telegram['clients']))
                            <div class="table table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="success">
                                        <td>Имя</td>
                                        <td>Телефон</td>
                                        <td align="center">Отправлять уведомления</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($telegram['clients'] as $id => $client)
                                        <tr>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->phone }}</td>
                                            <td><input type="checkbox" name="clients[{{ $id }}]" value="1"{{ $client->moderated ? ' checked' : '' }}></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" align="center">Нет запросов на рассылку!</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                @if($user->hasAccess(['settings.update']))
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection