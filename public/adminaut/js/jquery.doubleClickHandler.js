;(function ($) {

    var DoubleClickHandler = {

        sending: false,

        init: function ($form) {

            $form.on('submit', function (event) {

                if (this.sending) {
                    event.preventDefault();
                    return false;
                }

                this.sending = true;
                return true;
            });
        }
    };

    $.fn.doubleClickHandler = function () {
        return this.each(function () {
            DoubleClickHandler.init($(this));
        });
    };
})(jQuery);

// example:
// $(document).ready(function () {
//     $('#form').doubleClickHandler();
// });
