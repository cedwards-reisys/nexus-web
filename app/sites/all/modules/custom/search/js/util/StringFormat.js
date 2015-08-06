
(function (global) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Util.StringFormat requires FS');
    }

    var FS = global.FS;

    var StringFormat = {

        getTitleCase: function (str) {
            return str.replace(/(?:^|\s)\w/g, function(match) {
                return match.toUpperCase();
            });
        }

    };

    FS.Util.StringFormat = StringFormat;

})(typeof window === 'undefined' ? this : window);
