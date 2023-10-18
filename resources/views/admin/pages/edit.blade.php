@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Страницы
@endsection
@section('content')

    <h1>Редактирование страницы</h1>

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
        <form method="post" id="main_form">
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
                         'item' => $page,
                        ])
                        @if(old('template') == 'public.page' || (empty(old('template')) && $page->template == 'public.page'))
                            @include('admin.layouts.form.editor', [
                             'label' => 'Контент',
                             'key' => 'body',
                             'locale' => 'ru',
                             'item' => $page,
                             'languages' => $languages
                            ])
                        @else
                            @include('admin.pages.fields')
                        @endif
                        @include('admin.layouts.form.select', [
                         'label' => 'Шаблон',
                         'key' => 'template',
                         'options' => $templates,
                         'selected' => [old('template') ? old('template') : $page->template]
                        ])
                        @include('admin.layouts.form.select', [
                         'label' => 'Родительская страница',
                         'key' => 'parent_id',
                         'options' => $pages,
                         'selected' => [old('parent_id') ? old('parent_id') : $page->parent_id]
                        ])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.string', [
                         'label' => 'Порядок сортировки',
                         'key' => 'sort_order',
                         'item' => $page,
                         'languages' => []
                        ])
                        @include('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'status',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('status') ? old('status') : $page->status]
                        ])
                    </div>
                </div>
                @include('admin.layouts.seo')
                @if($user->hasAccess(['pages.update']))
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
        $(document).on('click', '.add-item', function(){
            var $this = $(this);
            var source = $this.closest('.repeater').parent();
            if(source.hasClass('disabled')){
                source.find('input, textarea, select').each(function(){
                    $(this).val('').prop('disabled', false);
                });
                source.find('.disabled').removeClass('disabled');
                source.removeClass('disabled');
            }else{
                var item = source.clone();
                var main_repeater = item.find('.repeater').first();
                main_repeater.attr('data-iterator', main_repeater.data('iterator')+1).data('iterator', main_repeater.data('iterator')+1);
                var parent_prefix = '';
                source.parents('.repeater').each(function(){
                    parent_prefix = $(this).data('parent')+'['+$(this).data('iterator')+']' + parent_prefix;
                });

                item.find('.image-container').each(function(){
                    $(this).find('.remove-image').parent().parent().remove();
                    $(this).find('.upload_image_button').show();
                });

                item.find('input, textarea, select').each(function(){
                    var prefix = $(this).data('prefix');
                    var name = '';
                    $(this).parents('.repeater').each(function(){
                        name = $(this).data('parent')+'['+$(this).data('iterator')+']' + name;
                    });
                    name = prefix + parent_prefix + name +'['+$(this).data('name')+']';
                    $(this).attr('name', name).val('');
                });
                $this.closest('.form-element').append(item);
                $this.remove();
            }
        });

        $(document).on('click', '.remove-item', function(){
            var repeater = $(this).closest('.repeater').parent();
            var prev = repeater.prev();
            if(prev.length){
                var add_button = $(this).prev();
                if(add_button.length){
                    repeater.prev().find('.btn-group').last().prepend(add_button);
                }
                repeater.remove();
            }else{
                repeater.find('input, textarea, select').each(function(){
                    $(this).val('').prop('disabled', true);
                });
                repeater.addClass('disabled');
            }
        });
    </script>

    <style>
        .repeater > div > div > .form-group:last-child{
            margin-bottom: 0;
        }
        .panel-body > .form-group > .row > .form-element > .row > .repeater{
            margin-bottom: 10px;
        }
        .repeater .repeater{
            margin-bottom: 10px;
        }
        .repeater .row:last-child > .repeater{
            margin-bottom:0;
        }
    </style>

    @if(old('template') == 'public.page' || (empty(old('template')) && $page->template == 'public.page'))
        @include('admin.layouts.mce', ['editors' => $editors])
    @else
        @php
            function getChildrenEditors($fields, $parent, $data, $lang){
                $editors = [];
                foreach($fields as $field){
                    foreach($data as $i => $d){
                        if($field->type == 'wysiwyg'){
                            $editors[] = 'fields'.$lang.$parent.$i.$field->slug;
                        }elseif($field->type == 'repeater' && isset($d->{$field->slug})){
                            $editors = array_merge($editors, getChildrenEditors($field->fields, $parent.$i.$field->slug, $d->{$field->slug}, $lang));
                        }
                    }
                }

                return $editors;
            }
            foreach($fields as $lang => $fields_lang){
                foreach($fields_lang as $field){
                    if($field->type == 'wysiwyg'){
                        $editors[] = 'fields'.$lang.$field->slug;
                    }elseif($field->type == 'repeater'){
                         $editors = array_merge($editors, getChildrenEditors($field->fields, $field->slug, $field->data, $lang));
                    }
                }
            }
        @endphp
        @include('admin.layouts.mce', ['editors' => $editors])
    @endif
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection