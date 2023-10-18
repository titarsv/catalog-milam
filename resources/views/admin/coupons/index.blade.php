@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Купоны
@endsection
@section('content')

    <h1>Купоны</h1>

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

    <div class="panel-group">
        <div class="panel panel-default">
            @if($user->hasAccess(['coupons.create']))
                <div class="panel-heading text-right">
                    {{--<button id="add_coupon" class="btn btn-primary">Добавить купон</button>--}}
                    <a href="/admin/coupons/create" class="btn btn-primary">Добавить купон</a>
                </div>
            @endif
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>Код</td>
                        <td>Размер скидки</td>
                        <td>Действителен до</td>
                        <td>Многоразовый</td>
                        <td>Статус</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ !empty($coupon->price) ? $coupon->price.' грн' : (!empty($coupon->percent) ? $coupon->percent.'%' : '') }}</td>
                            <td>{{ !empty($coupon->to) ? $coupon->to : '' }}</td>
                            <td>
                                <span class="{{ $coupon->disposable ? 'off' : 'on' }}" style="cursor: pointer;">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td>
                                <span class="{{ $coupon->used ? 'off' : 'on' }}" style="cursor: pointer;">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td class="actions" align="center">
                                @if($user->hasAccess(['coupons.view']))
                                    <a class="btn btn-primary" href="/admin/coupons/edit/{!! $coupon->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                @endif
                                @if($user->hasAccess(['coupons.delete']))
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $coupon->id }}', '{{ $coupon->code }}')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">Нет акционных предложений!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

    <div id="delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить купон <span id="delete-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm-delete">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function(){
            $('#add_coupon').click(function(){
                swal({
                    title: 'Создание купона',
                    html:
                    '<div class="swal2-content">' +
                    '<div class="form-group">' +
                    '<label for="code">Код:</label>' +
                    '<input id="code" name="code" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="form-group">\n' +
                    '\t<div class="row">\n' +
                    '\t\t<label class="col-sm-12">Размер скидки:</label>\n' +
                    '\t\t<div class="form-element col-sm-12">\n' +
                    '\t\t\t<div class="row">\n' +
                    '\t\t\t\t<div class="col-xs-12">\n' +
                    '\t\t\t\t\t<div class="input-group">\n' +
                    '\t\t\t\t\t\t<input type="text" class="form-control" id="sale" name="percent" value="">\n' +
                    '\t\t\t\t\t\t<div class="input-group-btn" id="coupon-price">\n' +
                    '\t\t\t\t\t\t\t<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">% <span class="caret"></span></button>\n' +
                    '\t\t\t\t\t\t\t<ul class="dropdown-menu pull-right">\n' +
                    '\t\t\t\t\t\t\t\t<li><a href="#" data-name="price">грн</a></li>\n' +
                    '\t\t\t\t\t\t\t\t<li><a href="#" data-name="percent">%</a></li>\n' +
                    '\t\t\t\t\t\t\t</ul>\n' +
                    '\t\t\t\t\t\t</div>\n' +
                    '\t\t\t\t\t</div>\n' +
                    '\t\t\t\t</div>\n' +
                    '\t\t\t</div>\n' +
                    '\t\t</div>\n' +
                    '\t</div>\n' +
                    '</div>' +
                    '</div>',
                    focusConfirm: false,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            let data = {};
                            data.code = $('#code').val();
                            data[$('#sale').attr('name')] = $('#sale').val();
                            $.post('/admin/coupons/create',
                                data,
                                function(response){
                                    if(response.result === 'success'){
                                        resolve(response.location);
                                    }else{
                                        reject(response.errors);
                                    }
                                });
                        });
                    }
                }).then(function(location) {
                    window.location=location;
                }, function(errors) {
                    if(typeof errors !== 'string'){
                        var message = '';
                        for(err in errors){
                            message += errors[err] + '<br>';
                        }
                        swal(
                            'Ошибка!',
                            message,
                            'error'
                        );
                    }
                });
            });

            $(document).on('click', '#coupon-price a', function(){
                var name = $(this).data('name');
                var text = $(this).text();

                $('#coupon-price button').html(text+' <span class="caret"></span>');
                $('#sale').attr('name', name);
            });
        });

        function confirmDelete(id, name) {
            $('#confirm-delete').attr('href', '/admin/coupons/delete/' + id);
            $('#delete-name').html(name);
            $('#delete-modal').modal();
        }
    </script>
@endsection