@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Шаблон страницы
@endsection
@section('content')

    <h1>Шаблон страницы "{{ $template->name }}.blade.php"</h1>

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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4>Поля</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                <div class="btn-group">
                                    <span class="btn btn-success" id="add_field" data-key="{{ count($template->fields) }}">Добавить поле</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body" id="fields">
                        @foreach($template->fields as $key => $field)
                            @include('admin.pages.templates.field', ['parent' => "fields[$key]"])
                        @endforeach
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

    <div class="hidden">
        @include('admin.pages.templates.field', ['field' => null, 'parent' => ''])
        @include('admin.pages.templates.fields.select', ['field' => null, 'parent' => ''])
        @include('admin.pages.templates.fields.repeater', ['field' => null, 'parent' => ''])
    </div>

    <script>
        jQuery(document).ready(function($){
            $('#add_field').click(function(e){
                e.preventDefault();
                var $this = $(this);
                var field = $('.hidden > .field').clone();
                var key = $this.data('key');
                field.find('input, select').each(function(){
                    $(this).attr('name', 'fields['+key+']'+$(this).attr('name')).attr('data-parent', 'fields['+key+']');
                });
                $('#fields').append(field);
                $this.data('key', key + 1);
            });

            $(document).on('change', '.field .type', function(){
                var $this = $(this);
                if($.inArray($this.val(), ['select', 'repeater']) !== -1){
                    var field = $('.hidden > .panel.'+$this.val()).clone();
                    var parent = $this.data('parent');
                    field.find('input, textarea, select').each(function(){
                        $(this).attr('name', parent+$(this).attr('name'));
                    });
                    field.find('.add-field').attr('data-parent', parent);
                    $this.closest('.field').find('.params').html(field);
                }else{
                    $this.closest('.field').find('.params').html('');
                }
            });

            $(document).on('click', '.field .add-field', function(e){
                e.preventDefault();
                var $this = $(this);
                var field = $('.hidden > .field').clone();
                var key = $this.data('key');
                var parent = $this.data('parent');
                field.find('input, select').each(function(){
                    $(this).attr('name', parent+'[fields]['+key+']'+$(this).attr('name')).attr('data-parent', parent+'[fields]['+key+']');
                });
                $this.closest('.panel').children('.fields').append(field);
                $this.data('key', key + 1);
            });

            $(document).on('click', '.field .remove-field', function(e){
                e.preventDefault();
                var $this = $(this);
                $this.closest('.field').remove();
            });
        });
    </script>
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection
@include('admin.layouts.footer')