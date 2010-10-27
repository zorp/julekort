ebst = {

    init: function() {
        $('.add-recipient').bind('click', function() {
            ebst.addRecipient();
        });
        $('.view-card').bind('click', function() {
            $('.flash').flash({
                swf: '/flash/ebst_julekort.swf',
                height: 440,
                width: 320,
                flashvars: {
                    greeting: $('.flash-text').html()
                }
            });
        }).trigger('click');
        $('textarea#greeting').autoGrow();
        $('textarea#greeting').bind('keyup', function() {
            var textarea = $(this).val();
            $('.flash-text').html(textarea);
        })
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
            },

            complete: function() {},
            error: function() {}
      });
    }

}

jQuery(document).ready(function($) {
    ebst.init();
});
