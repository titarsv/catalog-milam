@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Видеогаллерея
@endsection
@section('content')

    <h1>Редактирование видеогаллереи {{ $gallery->name }}</h1>

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
                        @include('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'locale' => 'ru',
                         'required' => true,
                         'item' => $gallery,
                        ])
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Превью</label>
                                <div class="form-element col-sm-3">
                                    @include('admin.layouts.form.image', [
                                     'key' => 'file_id',
                                     'image' => $gallery->image
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            @foreach($gallery->videos as $i => $video)
                                <div class="row video" data-id="{{ $i }}">
                                    <label class="col-sm-2 text-right">Видео</label>
                                    <div class="form-element col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="videos[{{ $i }}][link]" placeholder="Ссылка" value="{{ old("videos[$i][link]") ? old("videos[$i][link]") : $video->link }}">
                                                @if($errors->has("videos[$i][link]"))
                                                    <p class="warning" role="alert">{{ $errors->first("videos[$i][link]",':message') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="videos[{{ $i }}][name_ru]" value="{{ old("videos[$i][name_ru]") ? old("videos[$i][name_ru]") : $video->localize('ru', 'name') }}" placeholder="На русском">
                                                @if($errors->has("videos[$i][name_ru]"))
                                                    <p class="warning" role="alert">{{ $errors->first("videos[$i][name_ru]",':message') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="videos[{{ $i }}][name_ua]" value="{{ old("videos[$i][name_ua]") ? old("videos[$i][name_ua]") : $video->localize('ua', 'name') }}" placeholder="Українською">
                                                @if($errors->has("videos[$i][name_ua]"))
                                                    <p class="warning" role="alert">{{ $errors->first("videos[$i][name_ua]",':message') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-element col-sm-1">
                                        @include('admin.layouts.form.image', [
                                         'key' => "videos[$i][file_id]",
                                         'image' => $video->image
                                        ])
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="add_video">Добавить видео</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'visible',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('visible') ? old('visible') : $gallery->visible]
                        ])
                    </div>
                </div>
                @include('admin.layouts.seo')
                @if($user->hasAccess(['news.update']))
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

    <script>
        jQuery(document).ready(function($){
            $('#add_video').click(function(){
                var id = 0;
                $('.video').each(function(){
                    var video_id = parseInt($(this).data('id'));
                    if(video_id >= id){
                        id = video_id + 1;
                    }
                });
                $(this).parents('.row').before('<div class="row video" data-id="'+id+'">\n' +
                    '  <label class="col-sm-2 text-right">Видео</label>\n' +
                    '  <div class="form-element col-sm-9">\n' +
                    '    <div class="row">\n' +
                    '      <div class="col-sm-4">\n' +
                    '        <input type="text" class="form-control" name="videos['+id+'][link]" placeholder="Ссылка" value="">\n' +
                    '      </div>\n' +
                    '      <div class="col-sm-4">\n' +
                    '        <input type="text" class="form-control" name="videos['+id+'][name_ru]" value="" placeholder="На русском">\n' +
                    '      </div>\n' +
                    '      <div class="col-sm-4">\n' +
                    '        <input type="text" class="form-control" name="videos['+id+'][name_ua]" value="" placeholder="Українською">\n' +
                    '      </div>\n' +
                    '    </div>\n' +
                    '  </div>\n' +
                    '  <div class="form-element col-sm-1">\n' +
                    '    <div class="image-container">\n' +
                    '      <input type="hidden" name="videos['+id+'][file_id]" value="">\n' +
                    '      <div class="upload_image_button" data-type="single">\n' +
                    '        <div class="add-btn"></div>\n' +
                    '      </div>\n' +
                    '    </div>' +
                    '  </div>\n' +
                    '</div>');
            });
        });
    </script>
    <style>
        .video .image-container{
            max-width: 36px;
        }
        .video .image-container .remove-image, .video .image-container > div > div.add-btn::before {
            font-size: 24px;
            line-height: 22px;
        }
    </style>
    @include('admin.layouts.mce', ['editors' => $editors])
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection
