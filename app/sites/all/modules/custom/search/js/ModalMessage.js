
(function (global, $) {

    if (typeof global.FS === 'undefined') {
        throw new Error('MessageModal requires FS');
    }

    var FS = global.FS;

    var MessageModal = FS.Class.extend({

        init: function (options) {
            this.title = 'Default Title';
            this.message = 'Default message';
            this.closeButtonValue = 'Close';
            this.messageContainer = null;
            $.extend(this, options);
        },

        initialize: function() {
            var markup = [
                '<div class="modal fade" role="dialog">',
                    '<div class="modal-dialog" role="document">',
                        '<div class="modal-content">',
                            '<div class="modal-header">',
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
                                '<h4 class="modal-title">'+this.title+'</h4>',
                            '</div>',
                            '<div class="modal-body">',
                                this.message,
                            '</div>',
                            '<div class="modal-footer">',
                                '<button type="button" class="btn btn-primary" data-dismiss="modal">'+this.closeButtonValue+'</button>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>'];

            this.modal = $(markup.join("\n"));

            if ( this.messageContainer ) {
                this.modal.find('.modal-body').html($(this.messageContainer).clone());
            }

            $('body').append(this.modal);
            this.modal.modal({
                show: false
            });
        },

        show: function() {
            this.modal.modal('show');
        }

    });

    FS.MessageModal = MessageModal;

})(typeof window === 'undefined' ? this : window, jQuery);



