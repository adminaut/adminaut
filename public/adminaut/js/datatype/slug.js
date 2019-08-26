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

    var cylliric2Latin = function(str) {
        var a = {"Ё":"YO","Й":"I","Ц":"TS","У":"U","К":"K","Е":"E","Н":"N","Г":"G","Ш":"SH","Щ":"SCH","З":"Z","Х":"H","Ъ":"'","ё":"yo","й":"i","ц":"ts","у":"u","к":"k","е":"e","н":"n","г":"g","ш":"sh","щ":"sch","з":"z","х":"h","ъ":"'","Ф":"F","Ы":"I","В":"V","А":"a","П":"P","Р":"R","О":"O","Л":"L","Д":"D","Ж":"ZH","Э":"E","ф":"f","ы":"i","в":"v","а":"a","п":"p","р":"r","о":"o","л":"l","д":"d","ж":"zh","э":"e","Я":"Ya","Ч":"CH","С":"S","М":"M","И":"I","Т":"T","Ь":"'","Б":"B","Ю":"YU","я":"ya","ч":"ch","с":"s","м":"m","и":"i","т":"t","ь":"'","б":"b","ю":"yu"};

        return str.split('').map(function (char) {
            return a[char] || char;
        }).join("");
    }

    $(document).ready(function () {
        $('.slug-input').each(function () {
            $slugInput = $(this);
            $targetElement = $('input[name="' + $slugInput.data('target') + '"]').data('slug-input', $slugInput);

            if ($targetElement.length > 0) {
                if($slugInput.val() === "") {
                    $targetElement.on('keyup', function () {
                        $_slug = $(this).data('slug-input');
                        if (!$_slug.hasClass('slug-lock')) {
                            let _slug = $(this).val();

                            if ($slugInput.data('convertCylliric') === 1) {
                                _slug = cylliric2Latin(_slug);
                            }

                            $_slug.val(slug(_slug));
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
            let _slug = $targetElement.val();

            if ($slugInput.data('convertCylliric') === 1) {
                _slug = cylliric2Latin(_slug);
            }

            $slugInput.val(slug(_slug)).addClass('slug-lock');
        });
    })
})(jQuery);
