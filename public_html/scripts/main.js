ebst = {

    init: function() {

        if($('#greeting').length != 0) {
            var content = $('#greeting').val().replace(/\n/g, "<br>");
            $('.flash-text').html(content);
        }

        $('.add-recipient').bind('click', function() {
            ebst.addRecipient();
        });

        $('.view-card').bind('click', function() {
            ebst.binds.flash('.new .flash', 440, 320, false);
        }).trigger('click');

        $('.reload-card').bind('click', function() {
            ebst.binds.flash('.view .flash', 600, 440, true);
        }).trigger('click');

        $('#greeting').autogrow();
        $('#greeting').charCounter(450, {
            container: '<div></div>',
            format: "%1 karakterer tilbage"
        });
        $('#greeting').bind('keyup', function() {
            var content = $(this).val().replace(/\n/g, "<br>");
            $('.flash-text').html(content);
        });
    },

    binds: {
        flash: function(selector, h, w, isview) {
            var check_signature = ($('.signature').val() == 'true') ? 'true' : 'false';
            var use_signature = ($('.use-signature').val()) ? 'true' : 'false';
            var signature = (check_signature == 'true' || use_signature == 'true') ? 'true' : 'false';

            // @todo find a more elegant way of fixing ie's newline normalizing hell
            if(isview) {
                if($.browser.msie){
                    var greeting = $('.flash-text').html();
                } else {
                    var greeting = $('.flash-text').text();
                }
            } else {
                var greeting = $('.flash-text').html();
            }

            $(selector).flash({
                swf: '/flash/kort.swf',
                height: h,
                width: w,
                flashvars: $.param({
                    signature: signature,
                    greeting: greeting
                })
            });
        }
    },

    addRecipient: function() {
        var gid = $('#gid').val();
        $.ajax({
            url: '/addrecipient/format/html',
            type: 'POST',
            data: 'gid=' + gid,
            success: function(elements) {
                $('#gid').val(++gid);
                $(elements).insertBefore('#add_recipient-label')
                           .css({display: 'none'})
                           .fadeIn();
            }
      });
    }

}


jQuery(document).ready(function($) {
    ebst.init();
});