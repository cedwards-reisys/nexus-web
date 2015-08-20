(function (global, $) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Visualization.Component requires FS');
    }

    var FS = global.FS;

    var Component = FS.Class.extend({

        init: function (options) {
            this.filter = null;
            $.extend(this, options);
        },

        setFilter: function ( filter ) {
            this.filter = filter;
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

            return preparedQuery;
        },

        render: function() {}

    });

    FS.Visualization.Component = Component;

})(typeof window === 'undefined' ? this : window, jQuery);


