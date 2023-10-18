@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    SEO
@endsection
@section('content')

    <h1>Добавление SEO записи</h1>

    @if(session('message-error'))
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
                @include('admin.layouts.seo', ['required_url' => true])
                <div class="panel panel-default">
                    <div class="panel-body">
                        @include('admin.layouts.form.string', [
                         'label' => 'Тип страницы',
                         'key' => 'seotable_type',
                         'required' => true,
                         'item' => null,
                         'languages' => null
                        ])
                        @include('admin.layouts.form.string', [
                         'label' => 'ID записи',
                         'key' => 'seotable_id',
                         'item' => null,
                         'languages' => null
                        ])
                        @include('admin.layouts.form.string', [
                         'label' => 'Метод отображения',
                         'key' => 'action',
                         'item' => null,
                         'languages' => null
                        ])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('admin.layouts.mce', ['editors' => $editors])
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection
@include('admin.layouts.footer')

