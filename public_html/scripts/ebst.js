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

        $('textarea#greeting').autoGrow();
        $('textarea#greeting').bind('keyup', function() {
            var textarea = $(this).val();
            $('.flash-text').html(textarea);
        });
    },

    binds: {
        flash: function(selector, h, w) {
            $(selector).flash({
                swf: '/flash/ebst_julekort.swf',
                height: h,
                width: w,
                flashvars: {
                    greeting: $('.flash-text').html()
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
