(function(global, $, undefined){

    if (typeof global.FS === 'undefined') {
        throw new Error('SearchApp requires FS');
    }

    var FS = global.FS;

    var SearchApp = FS.Class.extend({

        init:function () {

        }

    });

    FS.SearchApp = new SearchApp();

})(typeof window === 'undefined' ? this : window, jQuery);
