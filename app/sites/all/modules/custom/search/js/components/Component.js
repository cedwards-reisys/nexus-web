(function (global, $) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Visualization.Component requires FS');
    }

    var FS = global.FS;

    var Component = FS.Class.extend({

        init: function (options) {
            this.filter = null;
            this.textSearch = null;
            $.extend(this, options);
        },

        setFilter: function ( query ) {
            if ( query ) {
                if (typeof query['$where'] !== 'undefined') {
                    this.filter = query['$where'];
                }
                if (typeof query['$q'] !== 'undefined') {
                    this.textSearch = query['$q'];
                }
            }
            return this;
        },

        getPreparedQuery: function() {

            var preparedQuery = $.extend({}, this.query);
            if (this.filter) {
                if (typeof preparedQuery['$where'] !== 'undefined') {
                    preparedQuery['$where'] += ' AND (' + this.filter + ')';
                } else {
                    preparedQuery['$where'] = this.filter;
                }
            }

            if ( this.textSearch ) {
                preparedQuery['$q'] = this.textSearch;
            }

            return preparedQuery;
        },

        render: function() {}

    });

    FS.Visualization.Component = Component;

})(typeof window === 'undefined' ? this : window, jQuery);


