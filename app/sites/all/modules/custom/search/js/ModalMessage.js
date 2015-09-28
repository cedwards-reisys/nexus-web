
(function (global, $) {

    if (typeof global.FS === 'undefined') {
        throw new Error('MessageModal requires FS');
    }

    var FS = global.FS;

    var MessageModal = FS.Class.extend({

        init: function (options) {
            this.id = 'messageModal';
            this.title = 'Default Title';
            this.message = 'Default message';
            this.closeButtonValue = 'Close';
            this.messageContainer = null;
            $.extend(this, options);
        },

        initialize: function() {
            var markup = [
                '<div class="modal fade" role="dialog" id="'+this.id+'" style="top:50%;margin-top:-200px;">',
                    '<div class="modal-dialog" role="document">',
                        '<div class="modal-content panel-warning">',
                            '<div class="modal-header panel-heading">',
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
                                '<h4 class="modal-title">'+this.title+'</h4>',
                            '</div>',
                            '<div class="modal-body">',
                                this.message,
                            '</div>',
                            '<div class="modal-footer">',
                                '<button type="button" style="float:right;" class="btn btn-warning" data-dismiss="modal">'+this.closeButtonValue+'</button>',
                                '<div class="checkbox" style="float:left;"><label><input type="checkbox"> Don\'t show this again.</label></div>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>'];

            this.modal = $(markup.join("\n"));

            if ( this.messageContainer ) {
                this.modal.find('.modal-body').html($(this.messageContainer).clone(true,true));
            }

            $('body').append(this.modal);

            this.modal.modal({
                show: false
            });

            var _this = this;
            this.modal.find('input[type=checkbox]').on('click',function(){
                if ( $(this).is(':checked') ) {
                    _this.savePreference({
                        display: false
                    });
                } else {
                    _this.savePreference({
                        display: true
                    });
                }
            });

        },

        show: function() {
            var display = Cookies.get(this.id);
            if ( typeof display === 'undefined' || display === true ) {
                this.modal.modal('show');
            }
        },

        savePreference: function (pref) {
            Cookies.remove(this.id);
            Cookies.set(this.id, pref.display,{expires:365});
        }

    });

    FS.MessageModal = MessageModal;

})(typeof window === 'undefined' ? this : window, jQuery);



