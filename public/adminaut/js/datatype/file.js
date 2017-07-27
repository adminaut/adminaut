(function($){
    $('body').on('click', '.datatype-file .file-remove', function(e){
        e.preventDefault();

        var $container = $(this).parents('.datatype-file');
        $container.html($('<input />').attr($container.data('attributes')));
    });
})(jQuery);