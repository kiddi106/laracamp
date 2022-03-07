var xhr;
var _orgAjax = jQuery.ajaxSettings.xhr;
jQuery.ajaxSettings.xhr = function () {
    xhr = _orgAjax();
    return xhr;
};

// $('body').on('shown.bs.modal', '.modal', function() { 
//     $(this).find('.datepicker').each(function() {
//         var dropdownParent = $(document.body);
//         if ($(this).parents('.modal.in:first').length !== 0)
//         dropdownParent = $(this).parents('.modal.in:first');
//         $(this).datepicker({
//             autoclose: true,
//             format: 'dd/mm/yyyy'
//         }).inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
//     });
//     $(this).find('.time_vehicle').each(function() {
//         var dropdownParent = $(document.body);
//         if ($(this).parents('.modal.in:first').length !== 0)
//         dropdownParent = $(this).parents('.modal.in:first');
//         $(this).inputmask("datetime", {
//             inputFormat: "HH:MM",
//             outputFormat: "HH:MM",
//             inputEventOnly: true
//         });
//     });
//     $(this).find('select').each(function() {
//         var dropdownParent = $(document.body);
//         if ($(this).parents('.modal.in:first').length !== 0)
//         dropdownParent = $(this).parents('.modal.in:first');
//         $(this).select2({
//             dropdownParent: dropdownParent,
//             theme: 'bootstrap4'
//         });
//     });
// });

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
        }
    });
    
    $('#modal').modal('show');
});

$('#modal-btn-save').click(function (event) {
    event.preventDefault();

    var form = $('#modal-body form'),
        url = form.attr('action'),
        method = $('input[name=_method]').val() == undefined ? 'POST' : 'PUT';
        
    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');

    $.ajax({
        url : url,
        method: method,
        data : form.serialize(),
        success: function (response) {
            Swal.fire('Please wait');
            Swal.showLoading();
            form.trigger('reset');
            $('#modal').modal('hide');
            $('#datatable').DataTable().ajax.reload();
            // alert(response);
            Swal.fire({
                type : 'success',
                title : 'Success!',
                text : 'Data has been saved!'
            });
        },
        error : function (xhr) {
            var res = xhr.responseJSON;
            if ($.isEmptyObject(res) == false) {
                $.each(res.errors, function (key, value) {
                    var id = $("*[name='"+ key + "']").attr('id');
                    $('#' + id)
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .addClass('has-error');
                        
                    $('<span class="help-block invalid-feedback"><strong>' + value + '</strong></span>').insertAfter('#' + id);
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
        title: 'Are you sure want to delete ' + title + ' ?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
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
                        type: 'success',
                        title: 'Success!',
                        text: 'Data has been deleted!'
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        }
    });
});

$('body').on('click', '.btn-action', function (event) {
    event.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Are you sure want to ' + title + ' ?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, ' + title
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    '_method': 'GET',
                    // '_token': csrf_token
                },
                success: function (response) {
                    $('#datatable').DataTable().ajax.reload();
                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        text: 'Data has been changed!'
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
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


$('body').on('click', '.btn-approve', function (event) {
    event.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Are you sure want to Approve ' + title + ' ?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Approve it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    '_method': 'GET',
                    // '_token': csrf_token
                },
                success: function (response) {
                    $('#datatable').DataTable().ajax.reload();
                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        text: 'Data has been Approved!'
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        }
    });
});
