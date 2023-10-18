@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Модули
@endsection
@section('content')

    <h1>{!! $module->name !!}</h1>

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
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr class="success">
                                <td>Название бренда</td>
                                <td>Логотип</td>
                                <td>Рекомендованные</td>
                                <td>В меню</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        @if(!empty($brand->image))
                                        <img src="{{ $brand->image->url() }}" alt="">
                                        @endif
                                    </td>
                                    <td><input type="checkbox" name="home[]" value="{{ $brand->id }}"{{ !empty($settings['home']) && in_array($brand->id, $settings['home']) ? ' checked' : '' }}></td>
                                    <td><input type="checkbox" name="menu[]" value="{{ $brand->id }}"{{ !empty($settings['menu']) && in_array($brand->id, $settings['menu']) ? ' checked' : '' }}></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" align="center">Нет брендов!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($user->hasAccess(['modules.update']))
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