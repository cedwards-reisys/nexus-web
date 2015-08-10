(function(global, $, undefined){

    if (typeof global.FS === 'undefined') {
        throw new Error('SearchApp requires FS');
    }

    var FS = global.FS;

    var SearchApp = FS.Class.extend({

        init:function () {

        },

        getAwardTypes: function () {
            return [
                'A: BPA CALL',
                'A: GWAC',
                'B: IDC',
                'BOA Basic Ordering Agreement',
                'BPA Blanket Purchase Agreement',
                'BPA Call Blanket Purchase Agreement',
                'B: PURCHASE ORDER',
                'C: DELIVERY ORDER',
                'C: FSS',
                'D: BOA',
                'DCA Definitive Contract',
                'D: DEFINITIVE CONTRACT',
                'DO Delivery Order',
                'E: BPA',
                'F: COOPERATIVE AGREEMENT',
                'FSS Federal Supply Schedule"',
                'G: GRANT FOR RESEARCH',
                'GWAC Government Wide Acquisition Contract',
                'IDC Indefinite Delivery Contract',
                'PO Purchase Order',
                'S: FUNDED SPACE ACT AGREEMENT',
                'T: TRAINING GRANT'
            ];
        }

    });

    FS.SearchApp = new SearchApp();

})(typeof window === 'undefined' ? this : window, jQuery);
