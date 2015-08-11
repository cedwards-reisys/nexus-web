(function(global, $, undefined){

    if (typeof global.FS === 'undefined') {
        throw new Error('SearchApp requires FS');
    }

    var FS = global.FS;

    var SearchApp = FS.Class.extend({

        init:function () {

            this.API_HOST = 'https://fedspending.demo.socrata.com/resource/nfu7-rhaq.json';
            this.ROWS_PER_PAGE = 20;

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
        },

        run: function() {
            var _this = this;
            var Uri = new FS.Util.UriHandler();
            $('#searchInputKeywords').find('input').val(Uri.getParam('keywords'));


            var searchResultsTable = $('#searchResults').dataTable( {
                processing: true,
                serverSide: true,
                searching: false,
                iDisplayLength: _this.ROWS_PER_PAGE,
                bLengthChange: false,
                order: [[ 2,'desc']],
                language: {
                    info: 'Showing _START_ to _END_ of _MAX_ records',
                    processing: '<div class="dataTableFetching"></div>'
                },
                columns: [
                    {
                        data: 'vendorname',
                        name: 'vendorname'
                    },
                    {
                        data: 'piid',
                        name: 'piid',
                        className: 'data-type-piid'
                    },
                    {
                        data: 'dollarsobligated',
                        name: 'dollarsobligated',
                        render: function(data, type, row, meta){
                            if (type === 'display') {
                                return (data=='') ? '' : FS.Util.NumberFormat.getCurrency(data,0);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'signeddate',
                        name: 'signeddate',
                        render: function(data, type, row, meta){
                            if (type === 'display') {
                                return (data=='') ? '' : FS.Util.DateFormat.getShortUsDate(data);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'contractactiontype',
                        name: 'contractactiontype'
                    },
                    {
                        data: 'agencyid',
                        name: 'agencyid'
                    },
                    {
                        data: 'fundingrequestingagencyid',
                        name: 'fundingrequestingagencyid'
                    }
                ],
                fnDrawCallback: function() {
                    $('[data-toggle="popover"]').popover();
                },
                fnServerData: function  ( sSource, aoData, fnCallback, oSettings ) {
                    var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
                    var columnNames = ['descriptionofcontractrequirement','vendorname','piid','dollarsobligated','signeddate','contractactiontype','agencyid','fundingrequestingagencyid'];
                    var order = columnNames[oSettings.aaSorting[0][0]+1] + ' ' + oSettings.aaSorting[0][1];

                    var query = {
                        '$select': columnNames.join(','),
                        '$order': order,
                        '$offset': page * _this.ROWS_PER_PAGE,
                        '$limit': _this.ROWS_PER_PAGE
                    };

                    // Keyword Input
                    var textSearch = $('#searchInputKeywords').find('input').val();
                    if ( textSearch ) {
                        $('#keywordsText').html(textSearch);

                        //Uri.addParam('keywords',textSearch);
                        //window.history.pushState({keywords: textSearch}, 'Search Results', Uri.getURI());

                        //query['$q'] = textSearch;

                        var preparedTextSearch = textSearch.replace(/'/g, "''").toUpperCase();
                        var textFields = ['vendorname','agencyid','fundingrequestingagencyid','descriptionofcontractrequirement','dunsnumber','piid'];
                        var textQueries = [];
                        for ( var i= 0,len=textFields.length;i<len;i++) {
                            textQueries.push('UPPER(' + textFields[i] + ') like \'%' + preparedTextSearch + '%\'');
                        }
                        query['$where'] = '(' + textQueries.join(' OR ') + ') ';
                    }

                    // Award Amount
                    var awardAmountSearch = $('#awardAmountInput').val();
                    var awardAmountOperatorSearch = $('#awardAmountOperatorInput').val();

                    if ( awardAmountSearch && awardAmountOperatorSearch ) {
                        if ( typeof query['$where'] === 'undefined' ) {
                            query['$where'] = '';
                        } else {
                            query['$where'] += ' AND ';
                        }
                        query['$where'] += 'dollarsobligated ' + awardAmountOperatorSearch + ' ' + parseInt(awardAmountSearch) + ' ';
                    }

                    // Award Id Input
                    var awardIdSearch = $('#awardIdInput').val();
                    if ( awardIdSearch ) {
                        if ( typeof query['$where'] === 'undefined' ) {
                            query['$where'] = '';
                        } else {
                            query['$where'] += ' AND ';
                        }
                        query['$where'] += 'UPPER(piid) = \''+awardIdSearch.toUpperCase()+'\' ';
                    }

                    // Recipient Name Input
                    var recipientNameSearch = $('#recipientNameInput').val();
                    if ( recipientNameSearch ) {
                        if ( typeof query['$where'] === 'undefined' ) {
                            query['$where'] = '';
                        } else {
                            query['$where'] += ' AND ';
                        }
                        query['$where'] += 'UPPER(vendorname) = \''+recipientNameSearch.toUpperCase()+'\' ';
                    }

                    // Contracting Agency Name Input
                    var contractAgencyNameSearch = $('#contractAgencyNameInput').val();
                    if ( contractAgencyNameSearch ) {
                        if ( typeof query['$where'] === 'undefined' ) {
                            query['$where'] = '';
                        } else {
                            query['$where'] += ' AND ';
                        }
                        query['$where'] += 'UPPER(agencyid) = \''+contractAgencyNameSearch.toUpperCase()+'\' ';
                    }

                    // Award Type Input
                    var awardTypeSearch = $('#awardTypeInput').find('input:checked');
                    if ( awardTypeSearch ) {
                        var types = [];
                        $.each(awardTypeSearch,function(k,item){
                            types.push('contractactiontype = \''+$(item).val()+'\'');
                        });

                        if ( types.length ) {

                            if (typeof query['$where'] === 'undefined') {
                                query['$where'] = '';
                            } else {
                                query['$where'] += ' AND ';
                            }

                            query['$where'] += '('+types.join(' OR ')+')';
                        }
                    }


                    $('#searchTransactionCount').find('dd').html('<div class="dataFetching"></div>');
                    $('#searchContractCount').find('dd').html('<div class="dataFetching"></div>');
                    $('#searchTransactionSum').find('dd').html('<div class="dataFetching"></div>');

                    oSettings.jqXHR = $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: _this.API_HOST,
                        data: query
                    }).done(function (data) {
                        var json = {};
                        if ( typeof data['error'] === 'undefined' ) {

                            json.iTotalDisplayRecords = _this.ROWS_PER_PAGE;
                            for ( var i = 0, ilen = data.length; i < ilen; i++ ) {
                                for ( var j = 0, jlen = columnNames.length; j < jlen; j++ ) {
                                    if ( typeof data[i][columnNames[j]] == 'undefined' ) {
                                        data[i][columnNames[j]] = '';
                                    } else {
                                        if (j === 2) {
                                            data[i][columnNames[j]] = '<span data-toggle="popover" title="Details" data-content="'+data[i][columnNames[0]]+'">'+data[i][columnNames[j]]+'</span>';
                                        }
                                    }
                                }
                            }
                            json.aaData = data;

                            var countQuery = {
                                '$select': 'count(1)'
                            };

                            if ( query['$where'] ) {
                                countQuery['$where'] = query['$where'];
                            }

                            if ( query['$q'] ) {
                                countQuery['$q'] = query['$q'];
                            }

                            $.ajax({
                                url: _this.API_HOST,
                                type: 'GET',
                                dataType: 'json',
                                data: countQuery
                            }).done(function( data ) {
                                $('#searchTransactionCount').find('dd').html(FS.Util.NumberFormat.getString(data[0].count_1, 0));
                                $('#searchContractCount').find('dd').html(FS.Util.NumberFormat.getString(data[0].count_1, 0));

                                json.iTotalDisplayRecords = data[0].count_1;
                                json.iTotalRecords = data[0].count_1;
                                fnCallback(json);
                            }).fail(function(){

                                $('#searchTableWrapper').hide();
                                $('#searchErrorMessage').show();

                            });

                            var sumQuery = {
                                '$select': 'sum(dollarsobligated)'
                            };

                            if ( query['$where'] ) {
                                sumQuery['$where'] = query['$where'];
                            }

                            if ( query['$q'] ) {
                                sumQuery['$q'] = query['$q'];
                            }

                            $.ajax({
                                url: _this.API_HOST,
                                type: 'GET',
                                dataType: 'json',
                                data: sumQuery
                            }).done(function( data ) {
                                $('#searchTransactionSum').find('dd').html(FS.Util.NumberFormat.getCurrency(data[0].sum_dollarsobligated,0));
                            }).fail(function(){

                                $('#searchTableWrapper').hide();
                                $('#searchErrorMessage').show();

                            });


                        } else {

                            console.log(data);

                            $('#searchTableWrapper').hide();
                            $('#searchErrorMessage').show();

                        }
                    }).fail(function (jqXHR, textStatus, errorThrown) {

                        console.log(jqXHR);

                        $('#searchTableWrapper').hide();
                        $('#searchErrorMessage').show();

                    });
                }
            });


            $('#searchInputKeywords').find('button').on('click',function(){
                searchResultsTable.api().ajax.reload();
            });

            $('#searchInputKeywords').find('input').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 ) {
                    searchResultsTable.api().ajax.reload();
                    return false;
                }
            });

            function initAwardTypeForm() {
                var awardTypes = FS.SearchApp.getAwardTypes();
                var html = '';
                for ( var i = 0, len=awardTypes.length; i < len; i++ ) {
                    html += '<div class="checkbox input-sm">';
                    html += '  <label>';
                    html += '    <input type="checkbox" value="'+awardTypes[i]+'">';
                    html += '    '+awardTypes[i];
                    html += '  </label>';
                    html += '</div>';
                }

                var checkboxes = $(html);
                checkboxes.find('input[type=checkbox]').on('click',function(){
                    searchResultsTable.api().ajax.reload();
                });

                $('#awardTypeInput').append(checkboxes);

            }
            initAwardTypeForm();


            $('#awardIdInput').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 && $(this).val() ) {
                    searchResultsTable.api().ajax.reload();
                    return false;
                }
            });

            $('#recipientNameInput').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 && $(this).val() ) {
                    searchResultsTable.api().ajax.reload();
                    return false;
                }
            });

            // Amount form controller
            $('#awardAmountInput').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 && $(this).val() ) {
                    searchResultsTable.api().ajax.reload();
                    return false;
                }
            });

            $('#awardAmountOperatorInput').on('change',function(e){
                if ( $('#awardAmountInput').val() ) {
                    searchResultsTable.api().ajax.reload();
                }
            });



        }

    });

    FS.SearchApp = new SearchApp();

    FS.SearchApp.run();

})(typeof window === 'undefined' ? this : window, jQuery);
