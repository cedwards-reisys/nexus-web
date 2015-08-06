
(function (global) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Util.NumberFormat requires FS');
    }

    var FS = global.FS;

    var NumberFormat = {

        getCurrency: function( number, c, d, t ) {
           return '$'+this.getString(number, c, d, t);
        },

        getString: function( n, c, d, t ) {
            var c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? '.' : d,
                t = t == undefined ? ',' : t,
                s = n < 0 ? '-' : '',
                i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + '',
                j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
        }

    };

    FS.Util.NumberFormat = NumberFormat;

})(typeof window === 'undefined' ? this : window);
