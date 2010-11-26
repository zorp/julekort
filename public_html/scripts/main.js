var verkcard = {

    init: function() {

        if($('#greeting').length != 0) {
            var content = $('#greeting').val().replace(/\n/g, "<br>");
            $('.flash-text').html(content);
        }

        $('.add-recipient').bind('click', function() {
            verkcard.addRecipient();
        });
        $('.remove-recipient').bind('click', function() {
            verkcard.removeRecipient();
        });

        $('.view-card').bind('click', function() {
            verkcard.binds.flash('.new .flash', 440, 320, false);
        }).trigger('click');

        $('.reload-card').bind('click', function() {
            verkcard.binds.flash('.view .flash', 600, 440, true);
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
                encodeParams: true,
                flashvars: {
                    greeting: greeting
                }
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
                $(elements).insertBefore('#recipients')
                           .css({display: 'none'})
                           .fadeIn();
            }
      });
    },

    removeRecipient: function() {
        var gid = $('#gid').val();
        if(gid > 1) {
            --gid;
            $('#Recipient-' + gid + '-to_name_' + gid + '-label').remove();
            $('#Recipient-' + gid + '-to_name_' + gid + '-element').remove();
            $('#Recipient-' + gid + '-to_email_' + gid + '-label').remove();
            $('#Recipient-' + gid + '-to_email_' + gid + '-element').remove()
            $('#gid').val(gid);
        }
    }

}


jQuery(document).ready(function($) {
    verkcard.init();
});
