(function (global, $) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Visualization.TotalAmount requires FS');
    }

    var FS = global.FS;

    var TotalAmount = FS.Visualization.Component.extend({

        init: function (options) {
            this._super(options);
        },

        render: function() {
            var _this = this;

            $(this.container).find('dd').html('<div class="dataFetching"></div>');

            $.ajax({
                url: this.api_host,
                type: 'GET',
                dataType: 'json',
                data: this.getPreparedQuery()
            }).done(function( data ) {

                $(_this.container).find('dd').html(FS.Util.NumberFormat.getCurrency(data[0].total,0));

                if ( typeof _this.doneCallback === 'function' ) {
                    _this.doneCallback();
                }

            }).fail(function(){

                $(_this.container).find('dd').html('-');

                if ( typeof _this.failCallback === 'function' ) {
                    _this.failCallback();
                }
            });
        }

    });

    FS.Visualization.TotalAmount = TotalAmount;

})(typeof window === 'undefined' ? this : window, jQuery);


