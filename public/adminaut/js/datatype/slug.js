(function ($) {
    var slug = function (str) {
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
        $('.slug-input').each(function () {
            $slugInput = $(this);
            $targetElement = $('input[name="' + $slugInput.data('target') + '"]').data('slug-input', $slugInput);

            if ($targetElement.length > 0) {
                if($slugInput.val() === "") {
                    $targetElement.on('keyup', function () {
                        $_slug = $(this).data('slug-input');
                        if (!$_slug.hasClass('slug-lock')) {
                            $_slug.val(slug($(this).val()));
                        }
                    }).on('blur', function () {
                        $(this).data('slug-input').addClass('slug-lock');
                    });
                }

                $slugInput.parent('.input-group').append('<span class="input-group-btn"><button type="button" class="btn btn-default btn-flat slug-refresh"><i class="fa fa-refresh"></i></button></span>');
            }
        });

        $('body').on('click', '.slug-refresh', function() {
            $slugInput = $(this).parents('.input-group').find('input.slug-input');
            $targetElement = $('input[name="' + $slugInput.data('target') + '"]');

            $slugInput.val(slug($targetElement.val())).addClass('slug-lock');
        });
    })
})(jQuery);
