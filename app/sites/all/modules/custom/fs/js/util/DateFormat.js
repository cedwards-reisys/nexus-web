
(function (global) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Util.DateFormat requires FS');
    }

    var FS = global.FS;

    var DateFormat = {

        getShortUsDate: function( date ) {

            if ( typeof date == 'undefined' ) {
                return '';
            }

            var date = new Date(date);
            return ('0' + (date.getMonth() + 1)).slice(-2) + '/' + ('0' + date.getDate()).slice(-2) + '/' + date.getFullYear();
        }

    };

    FS.Util.DateFormat = DateFormat;

})(typeof window === 'undefined' ? this : window);
