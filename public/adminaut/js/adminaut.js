function appendScript(filepath) {
    if ($('head script[src="' + filepath + '"]').length > 0)
        return;

    var ele = document.createElement('script');
    ele.setAttribute("type", "text/javascript");
    ele.setAttribute("src", filepath);
    $('head').append(ele);
}

function appendStyle(filepath) {
    if ($('head link[href="' + filepath + '"]').length > 0)
        return;

    var ele = document.createElement('link');
    ele.setAttribute("type", "text/css");
    ele.setAttribute("rel", "Stylesheet");
    ele.setAttribute("href", filepath);
    $('head').append(ele);
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
    });
})(jQuery);
