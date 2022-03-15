var xhr;
var _orgAjax = jQuery.ajaxSettings.xhr;
jQuery.ajaxSettings.xhr = function () {
    xhr = _orgAjax();
    return xhr;
};

$('body').on('click', '.modal-show', function (event) {
    event.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title');

    $('#modal-title').text(title);
    $('#modal-btn-save').removeClass('hide')
        .text(me.hasClass('edit') ? 'Update' : 'Create');

    $.ajax({
        url: url,
        dataType: 'html',
        success: function (response) {
            if (xhr.responseURL === loginUrl) {
                location.reload();
            }
            $('#modal-body').html(response);
            $('#modal').modal('show');
        }
    });
});

$('#modal-btn-save').click(function (event) {
    event.preventDefault();

    var form = $('#modal-body form'),
        url = form.attr('action'),
        method = $('input[name=_method]').val() == undefined ? 'POST' : 'PUT';

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');

    $.ajax({
        url: url,
        method: method,
        data: form.serialize(),
        success: function (response) {
            form.trigger('reset');
            $('#modal').modal('hide');
            $('#datatable').DataTable().ajax.reload();

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Data has been saved!',
                timer: 2000,
                confirmButtonColor: '#3085d6',
            });
        },
        error: function (xhr) {
            var res = xhr.responseJSON;
            if ($.isEmptyObject(res) == false) {
                $.each(res, function (key, value) {
                    $('#' + key)
                        .closest('.form-group')
                        .addClass('has-error')
                        .append('<span class="help-block"><strong>' + value + '</strong></span>');
                });
            }
        }
    })
});

$('body').on('click', '.btn-delete', function (event) {
    event.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Are you sure want to delete \n' + title + ' ?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    '_method': 'DELETE',
                    '_token': csrf_token
                },
                success: function (response) {
                    $('#datatable').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data has been deleted!',
                        timer: 2000,
                        confirmButtonColor: '#3085d6',
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        confirmButtonColor: '#3085d6',
                    });
                }
            });
        }
    });
});

$('body').on('click', '.btn-show', function (event) {
    event.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title');

    $('#modal-title').text(title);
    $('#modal-btn-save').addClass('hide');

    $.ajax({
        url: url,
        dataType: 'html',
        success: function (response) {
            $('#modal-body').html(response);
        }
    });

    $('#modal').modal('show');
});
