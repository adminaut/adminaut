(function ($) {
    var urlKey = function (str) {
        str = str.replace(/^\s+|\s+$/g, '').toLowerCase();
        var from = "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆÍÌÎÏŇÑÓÖÒÔÕØŘŔŠŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇíìîïňñóöòôõøðřŕšťúůüùûýÿžþÞĐđßÆa·/_,:;";
        var to = "AAAAAACCCDEEEEEEEEIIIINNOOOOOORRSTUUUUUYYZaaaaaacccdeeeeeeeeiiiinnooooooorrstuuuuuyyzbBDdBAa------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        str = str.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        return str;
    };

    $(document).ready(function () {
        $('.url-key-input').each(function () {
            $urlKeyInput = $(this);
            $targetElement = $('input[name="' + $urlKeyInput.data('target') + '"]').data('url-key-input', $urlKeyInput);

            if ($targetElement.length > 0) {
                if($urlKeyInput.val() === "") {
                    $targetElement.on('keyup', function () {
                        $_urlKeyInput = $(this).data('url-key-input');
                        if (!$_urlKeyInput.hasClass('url-key-lock')) {
                            $_urlKeyInput.val(urlKey($(this).val()));
                        }
                    }).on('blur', function () {
                        $(this).data('url-key-input').addClass('url-key-lock');
                    });
                }

                $urlKeyInput.parent('.input-group').append('<span class="input-group-btn"><button type="button" class="btn btn-default btn-flat url-key-refresh"><i class="fa fa-refresh"></i></button></span>');
            }
        });

        $('body').on('click', '.url-key-refresh', function() {
            $urlKeyInput = $(this).parents('.input-group').find('input.url-key-input');
            $targetElement = $('input[name="' + $urlKeyInput.data('target') + '"]');

            $urlKeyInput.val(urlKey($targetElement.val())).addClass('url-key-lock');
        });
    })
})(jQuery);
