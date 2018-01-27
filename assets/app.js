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
})

