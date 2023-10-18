@extends('public.layouts.main')

@section('meta')
    <title>{{ trans('app.Personal_information') }}</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
@endsection

@section('content')
    <main>
        <div class="section-account">
            <div class="container hidden-sm hidden-md hidden-lg">
                {!! Breadcrumbs::render('user') !!}
            </div>
            <div class="container">
                <div class="col">
                    <div class="account-wrapper tabs-wrapper">
                        <aside class="account-sidebar">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="account-hello">{{ trans('app.Hello') }},
                                <span>{{ $user->first_name }} {{ $user->last_name }}!</span>
                            </div>
                            <ul class="account-tabs">
                                <li class="active"><span>{{ trans('app.Personal_information') }}</span></li>
                                <li><a href="{{ base_url('/user/wishlist') }}">{{ trans('app.Wish_list') }}</a></li>
                                <li><a href="{{ base_url('/user/history') }}">{{ trans('app.Order_history') }}</a></li>
                                <li><a href="{{ base_url('/user/recommend') }}">{{ trans('app.Recommendations') }}</a></li>
                                <li><a href="{{ base_url('/user/payment') }}">{{ trans('app.Payment_and_delivery') }}</a></li>
                                <li><a href="{{ base_url('/user/contacts') }}">{{ trans('app.Contacts') }}</a></li>
                                <li><a href="{{ base_url('/logout') }}">{{ trans('app.Exit') }}</a></li>
                            </ul>
                        </aside>
                        <div class="account-main">
                            <div class="account-content tabs-content active">
                                <span class="account-title">{{ trans('app.Personal_information') }}</span>
                                <span class="account-descr">{{--{{ trans('app.Fill_in_the_profile_and_get_a_10_discount') }}--}}</span>
                                <div class="personal-info">
                                    <div class="personal-info__form-wrapper personal-info__left">
                                        <span class="personal-info__title">{{ trans('app.Contact_Information') }}</span>
                                        <form method="post" class="personal-info__form saved">
                                            {!! csrf_field() !!}
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.Last_Name') }}</label>
                                                <input type="text" name="last_name" value="{{ $user->last_name }}" required disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.First_Name') }}</label>
                                                <input type="text" name="first_name" value="{{ $user->first_name }}" required disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.Surname') }}</label>
                                                <input type="text" name="patronymic" value="{{ $user->patronymic }}" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>E-mail</label>
                                                <input type="text" name="email" value="{{ $user->email }}" required disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.Date_of_birth') }}</label>
                                                <input type="date" name="user_birth" value="{{ is_object($user->user_data) ? $user->user_data->user_birth : '' }}" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.Phone') }}</label>
                                                <input type="text" name="phone" value="{{ is_object($user->user_data) ? $user->user_data->phone : '' }}" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.town') }}</label>
                                                <input type="text" name="city" value="{{ is_object($user->user_data) ? $user->user_data->city : '' }}" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.Gender') }}</label>
                                                <div class="radio-wrapper">
                                                    <input type="radio" name="gender" value="0" id="female"{{ is_object($user->user_data) && $user->user_data->gender == 0 ? ' checked' : '' }} disabled>
                                                    <label for="female">{{ trans('app.Women') }}</label>
                                                    <input type="radio" name="gender" value="1" id="male"{{ is_object($user->user_data) && $user->user_data->gender == 1 ? ' checked' : '' }} disabled>
                                                    <label for="male">{{ trans('app.Men') }}</label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn">{{ trans('app.SAVE') }}</button>
                                        </form>
                                        <div class="edit-btn">{{ trans('app.to_change') }}</div>
                                    </div>
                                    <div class="personal-info__form-wrapper personal-info__right">
                                        <span class="personal-info__title">{{ trans('app.Change_password') }}</span>
                                        <form action="{{ base_url('/user/updatepassword') }}" method="post" class="personal-info__form password-form saved">
                                            {!! csrf_field() !!}
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.Old_password') }}</label>
                                                <input type="password" name="old_password" placeholder="*********" disabled>
                                            </div>
                                            @if($errors->has('old_password'))
                                                <p class="warning" role="alert">{{ $errors->first('old_password',':message') }}</p>
                                            @endif
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.New_password') }}</label>
                                                <input type="password" name="password" placeholder="*********" disabled>
                                            </div>
                                            @if($errors->has('password'))
                                                <p class="warning" role="alert">{{ $errors->first('password',':message') }}</p>
                                            @endif
                                            <div class="input-wrapper">
                                                <label>{{ trans('app.Confirm_password') }}</label>
                                                <input type="password" name="password_confirmation" placeholder="*********" disabled>
                                            </div>
                                            <button type="submit" class="btn">{{ trans('app.SAVE') }}</button>
                                        </form>
                                        <div class="edit-btn">{{ trans('app.to_change') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection