$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change', '.js_currency_switcher', function(){
        $.post(($('html').attr('lang') == 'ua' ? '' : '/'+$('html').attr('lang'))+"/set_currency", {currency: $(this).val()}, function(cart){
            location.reload();
        });
    });
});