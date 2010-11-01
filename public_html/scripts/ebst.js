ebst = {

    init: function() {
        $('.add-recipient').bind('click', function() {
            ebst.addRecipient();
        });

        $('.view-card').bind('click', function() {
            ebst.binds.flash('.new .flash', 440, 320);
        }).trigger('click');

        $('.reload-card').bind('click', function() {
            ebst.binds.flash('.view .flash', 600, 440);
        }).trigger('click');

        $('textarea#greeting').autoGrow().charCounter(450, {
            format: "%1 karakterer tilbage!"
        });
        $('textarea#greeting').bind('keyup', function() {
            var textarea = $(this).val();
            $('.flash-text').html(textarea);
        });
    },

    binds: {
        flash: function(selector, h, w) {
            var check_signature = ($('.signature').val() == 'true') ? 'true' : 'false';
            var use_signature = ($('.use-signature').val()) ? 'true' : 'false';
            var signature = (check_signature == 'true' || use_signature == 'true') ? 'true' : 'false';

            $(selector).flash({
                swf: '/flash/ebst_julekort.swf',
                height: h,
                width: w,
                flashvars: {
                    signature: signature,
                    greeting: escape($('.flash-text').html())
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
