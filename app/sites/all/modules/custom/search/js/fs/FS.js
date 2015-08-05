
(function(global) {

    if ( global.FS ) {
        throw new Error('FS has already been defined');
    }

    if ( !global.console ) {
        global.console = {log: function(){} };
    }


    if ( typeof global.jQuery === 'undefined' ) {
        throw new Error('FS requires jQuery');
    }

    var FS = {
        Util: {}
    };

    // add to global space
    global.FS = FS;

})(typeof window === 'undefined' ? this : window);
