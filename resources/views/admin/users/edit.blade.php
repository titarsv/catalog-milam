@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Запись блога
@endsection
@section('content')

    <h1>Редактирование пользователя {{ $u->first_name }} {{ $u->last_name }}</h1>

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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        {{--@if(in_array($u->roles->first()->slug, ['admin', 'manager', 'moderator']))--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">User ID:</label>--}}
                                {{--<label class="col-sm-10">--}}
                                    {{--{{ $u->id }}--}}
                                {{--</label>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Token:</label>--}}
                                {{--<label class="col-sm-10">--}}
                                    {{--{{ md5($u->password) }}--}}
                                {{--</label>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--@endif--}}

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Имя</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="first_name" value="{!! old('first_name') ? old('first_name') : $u->first_name !!}" />
                                    @if($errors->has('first_name'))
                                        <p class="warning" role="alert">{!! $errors->first('first_name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Фамилия</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="last_name" value="{!! old('last_name') ? old('last_name') : $u->last_name !!}" />
                                    @if($errors->has('last_name'))
                                        <p class="warning" role="alert">{!! $errors->first('last_name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Отчество</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="patronymic" value="{!! old('patronymic') ? old('patronymic') : $u->patronymic !!}" />
                                    @if($errors->has('patronymic'))
                                        <p class="warning" role="alert">{!! $errors->first('patronymic',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Почта</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="email" value="{!! old('email') ? old('email') : $u->email !!}" />
                                    @if($errors->has('email'))
                                        <p class="warning" role="alert">{!! $errors->first('email',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Телефон</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="phone" value="{!! old('phone') ? old('phone') : (empty($u->user_data) ? '' : $u->user_data->phone) !!}" />
                                    @if($errors->has('phone'))
                                        <p class="warning" role="alert">{!! $errors->first('phone',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Город</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="city" value="{!! old('city') ? old('city') : (empty($u->user_data) ? '' : $u->user_data->city) !!}" />
                                    @if($errors->has('city'))
                                        <p class="warning" role="alert">{!! $errors->first('city',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Пол</label>
                                <div class="form-element col-sm-10">
                                    <select name="gender" class="form-control" autocomplete="off">
                                        <option value="0"{{ empty($u->user_data) || empty($u->user_data->gender) ? ' selected' : '' }}>Женский</option>
                                        <option value="1"{{ !empty($u->user_data) && !empty($u->user_data->gender) ? ' selected' : '' }}>Мужской</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Группа</label>
                                <div class="form-element col-sm-10">
                                    <select name="role" class="form-control" autocomplete="off">
                                        <option value="manager"{{ isset($u->roles->first()->slug) && $u->roles->first()->slug == 'manager' ? ' selected' : '' }}>Менеджеры</option>
                                        <option value="moderator"{{ isset($u->roles->first()->slug) && $u->roles->first()->slug == 'moderator' ? ' selected' : '' }}>Модераторы</option>
                                        <option value="marketerr"{{ isset($u->roles->first()->slug) && $u->roles->first()->slug == 'marketer' ? ' selected' : '' }}>Маркетологи</option>
                                        <option value="user"{{ isset($u->roles->first()->slug) && ($u->roles->first()->slug == 'user' || $u->roles->first()->slug == 'unregistered') ? ' selected' : '' }}>Покупатели</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @include('admin.layouts.form.select', [
                        'label' => 'Персональные разрешения',
                        'key' => 'permissions',
                        'options' => $all_permissions,
                        'multiple' => true,
                        'selected' => $permissions
                       ])

                    </div>
                </div>
                @if($user->hasAccess(['users.update']))
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
@section('before_footer')
    @include('admin.media.assets')
@endsection
