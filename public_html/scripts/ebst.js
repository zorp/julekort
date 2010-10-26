ebst = {

    init: function() {
        $('.add-recipient').bind('click', function() {
            ebst.addRecipient();
        });
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
