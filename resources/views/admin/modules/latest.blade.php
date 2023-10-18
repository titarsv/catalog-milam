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
                    <div class="panel-heading">
                        <h4>Настройки модуля</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Статус</label>
                                <div class="form-element col-sm-10">
                                    <select name="status" class="form-control">
                                        @if($module->status)
                                            <option value="1" selected>Включить</option>
                                            <option value="0">Выключить</option>
                                        @else
                                            <option value="1">Включить</option>
                                            <option value="0" selected>Выключить</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Количество товаров для отображения</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<input type="text" name="quantity" class="form-control" value="{!! $settings->quantity !!}" />--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Товары</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group" style="position: relative">
                            <input type="text" name="search" class="form-control" id="live_search" value="" placeholder="Поиск">
                            <div id="live_search_results"></div>
                            <div id="in_action">
                                @include('admin.modules.products', ['products' => $products])
                            </div>
                        </div>
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

    <script>
        jQuery(document).ready(function($){
            var live_search_output = $('#live_search_results');
            $('#live_search').keyup(function(){
                var search = $(this).val();
                var target = $(this).attr('data-target');
                live_search_output.html('').hide();

                if (search.length > 1) {
                    var data = {
                        search: search
                    };
                    $.ajax({
                        url: '/admin/products/livesearch',
                        data: data,
                        method: 'POST',
                        dataType: 'JSON',
                        success: function(resp) {
                            var html = '<ul>';
                            $.each(resp, function(i, value){
                                if (value.empty) {
                                    html += '<li>';
                                    html += value.empty;
                                    html += '</li>';
                                } else {
                                    html += '<li class="selectable" data-name="' + value.name + '" data-id="' + value.product_id + '">';
                                    html += '<img src="'+value.image+'">';
                                    html += '<div>';
                                    html += '<b>'+value.name+'</b>';
                                    html += ' ('+value.price+'грн)';
                                    html += '</div>';
                                    html += '<button type="button" class="btn btn-primary add-to-action" data-id="' + value.product_id + '">Прикрепить к новинкам</button>';
                                    html += '</li>';
                                }
                            });
                            html += '</ul>';

                            $.each(live_search_output, function(i, value){
                                if($(value).attr('data-target') == target){
                                    $(value).html(html).show();
                                }
                            });
                        }
                    });
                } else {
                    live_search_output.hide();
                }
            });

            live_search_output.on('click', '.add-to-action', function(){
                var $this = $(this);
                var data = {
                    'product_id': $this.data('id')
                };

                $.post('/admin/modules/latest/add_product', data, function(response){
                    if(response.result == 'success'){
                        $('#in_action').html(response.html);
                        $this.parents('li').remove();
                    }
                });
            });

            $(document).on('click', '.remove-from-action', function(){
                var $this = $(this);
                var data = {
                    'product_id': $this.data('id')
                };

                $.post('/admin/modules/latest/remove_product', data, function(response){
                    if(response.result == 'success'){
                        $this.parents('tr').remove();
                    }
                });
            });
        });
    </script>

    <style>
        #live_search_results{
            position: absolute;
            background-color: #fff;
            max-height: 80vh;
            width: 100%;
            z-index: 2;
        }
        #live_search_results ul{
            list-style: none;
            padding: 0;
            border: 1px solid #ccc;
            margin: 0;
        }
        #live_search_results ul li{
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-right: 10px;
        }
        #live_search_results ul li img{
            height: 50px;
            width: 50px;
            object-fit: cover;
            margin-right: 15px;
        }
        #live_search_results ul li div{
            flex-grow: 1;
            margin-right: 15px;
        }
    </style>
@endsection
