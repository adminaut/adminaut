function convertObjectToInputs(object, inputNamePrefix, inputArray) {
    inputNamePrefix = undefined !== inputNamePrefix ? inputNamePrefix : "";
    inputArray = undefined !== inputArray ? inputArray : [];

    $.each(Object.keys(object), function(_, key) {
        if ("object" === typeof object[key]) {
            convertObjectToInputs(object[key], inputNamePrefix + '['+ key +']', inputArray);
        } else {
            inputArray.push($('<input>', {
                type: 'hidden',
                name: inputNamePrefix + '['+ key +']',
                value: object[key]
            }));
        }
    });

    return inputArray;
}

(function ($) {
    $(document).ready(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });

        $('body').on('collapsed.pushMenu', function () {
            Cookies.set('sidebar-collapsed', true);
        }).on('expanded.pushMenu', function () {
            Cookies.remove('sidebar-collapsed');
        });

        $("form:not(.filter) :input:visible:enabled:first").focus();
        $(document).on('focus', '.select2.select2-container', function (e) {
            if (e.originalEvent && $(this).find(".select2-selection--single").length > 0) {
                $(this).siblings('select').select2('open');
            }
        });
    });

    $(document).on('click', '#moduleEntityTable a.primary', function(e){
        if(e.altKey && $(this).parents('tr').find('a.edit').length > 0) {
            e.preventDefault();
            window.location = $(this).parents('tr').find('a.edit').attr('href');
        }
    });
    

})(jQuery);
