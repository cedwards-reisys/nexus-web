(function (global, $) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Visualization.TotalTransactions requires FS');
    }

    var FS = global.FS;

    var TotalTransactions = FS.Class.extend({

        init: function (options) {
            $.extend(this, options);
        },

        setSearchQuery: function ( searchQuery ) {
            if ( typeof searchQuery !== 'undefined' ) {
                this.query['$where'] = searchQuery;
            }
            return this;
        },

        render: function() {
            var _this = this;

            $(this.container).find('dd').html('<div class="dataFetching"></div>');

            $.ajax({
                url: this.api_host,
                type: 'GET',
                dataType: 'json',
                data: this.query
            }).done(function( data ) {

                $(_this.container).find('dd').html(FS.Util.NumberFormat.getString(data[0].total,0));

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

    FS.Visualization.TotalTransactions = TotalTransactions;

})(typeof window === 'undefined' ? this : window, jQuery);


