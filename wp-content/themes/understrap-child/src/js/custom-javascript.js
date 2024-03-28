// Add your custom JS here.
jQuery(document).ready(function ($) {
    var form = $('#add-form');

    $('#add-form input, #add-form textarea, #add-form select').on('blur', function () {
        $('#add-form input, #add-form textarea, #add-form select').removeClass('error');
        $('.notification').remove();
        $('#form_submit').val('Отправить');
    });
    var options = {
        url: ajax_form_object.url,
        data: {
            action: 'ajax_form_action',
            nonce: ajax_form_object.nonce
        },
        type: 'POST',
        dataType: 'json',
        beforeSubmit: function (xhr) {
            $('#form_submit').val('Отправляем...');
        },
        success: function (request, xhr, status, error) {

            if (request.success === true) {

                form.after('<div class="notification notification_accept">' + request.data + '</div>').slideDown();
                $('#form_submit').val('Отправить');
            } else {

                $.each(request.data, function (key, val) {
                    $('.form_' + key).addClass('error');
                    $('.form_' + key).after('<div class="notification notification_warning notification_warning_' + key + '">' + val + '</div>');
                });
                $('#form_submit').val('Что-то пошло не так...');
            }

            $('#add-form')[0].reset();
        },
        error: function (request, status, error) {
            $('#form_submit').val('Что-то пошло не так...');
        }
    };

    form.ajaxForm(options);
});