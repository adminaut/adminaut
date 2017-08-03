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
})(jQuery);
