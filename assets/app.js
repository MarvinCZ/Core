import 'bootstrap';
import 'trumbowyg';
$.trumbowyg.svgPath = '/icons.svg';

function submit(action, method, values) {
    let form = $('<form/>', {
        action: action,
        method: method
    })
    $.each(values, function(key, value) {
        form.append($('<input/>', {
            type: 'hidden',
            name: key,
            value: value
        }))
    })
    form.appendTo('body').submit()
}

function addFlashMessage(type, text) {
    let alertHTML = "<div class=\"alert alert-" + type + " alert-dismissible fade show\" role=\"alert\">" + text + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>"
    $('#flash-container').append(alertHTML);
}

$(document).ready(function () {
    $('a[data-method]').click(function(e) {
        e.preventDefault()
        let link = $(this).attr('href')
        let method = $(this).data('method')
        submit(link, 'post', {method: method});
    })

    $('#file').change(function () {
        let filename = $(this).val().split('\\').pop()
        $('#selected-file').val(filename)
    })

    $('textarea.editor').trumbowyg();

    $('form.ajax').submit(function (e) {
        e.preventDefault()
        let form = $(this)
        let formData = form.serialize()
        let action = form.attr('action')
        let method = form.attr('method')

        $.ajax(action, {
            data: formData,
            method: method
        }).done(function (data) {
            form.closest('.modal').modal('hide')
            let flashMessages = JSON.parse(data).flashMessages
            $.each(flashMessages, function (i, flashMessage) {
                addFlashMessage(flashMessage.type, flashMessage.message)
            })
        })
    })
})

