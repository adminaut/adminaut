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
    });

    $(document).on('click', '#moduleEntityTable a.primary', function(e){
        if(e.altKey && $(this).parents('tr').find('a.edit').length > 0) {
            e.preventDefault();
            window.location = $(this).parents('tr').find('a.edit').attr('href');
        }
    });
})(jQuery);
