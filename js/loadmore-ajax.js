(function ($) {
    'use strict';

    //  Startup load more button Ajax

    var $loadbutton = $('.loadAjax');

    if ($loadbutton.length) {

        var postNumber = startuploadajax.postNumber,
            Incr = 0;
        //
        $loadbutton.on('click', function () {


            Incr = Incr + parseInt(postNumber);

            var $button = $(this),
                $data;

            $data = {
                'action': 'startup_startup_ajax',
                'postNumber': postNumber,
                'postIncrNumber': Incr,
                'elsettings': startuploadajax.elsettings
            };

            $.ajax({

                url: startuploadajax.action_url,
                data: $data,
                type: 'POST',


                success: function (data) {

                    $('.startup-startup-load').html(data);

                    var $container = $('.startup-startup');

                    $container.isotope('reloadItems').isotope({
                        itemSelector: '.single_gallery_item',
                        percentPosition: true,
                        masonry: {
                            columnWidth: '.single_gallery_item'
                        }
                    });

                    var loaditems = parseInt(Incr) + parseInt(postNumber);

                    if (startuploadajax.totalitems == loaditems) {
                        $button.hide();
                    }

                }

            });

            return false;

        });


    }


})(jQuery);