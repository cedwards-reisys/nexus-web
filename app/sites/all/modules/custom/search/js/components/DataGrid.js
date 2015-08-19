(function (global, $ ) {

    if (typeof global.FS === 'undefined') {
        throw new Error('Util.DateFormat requires FS');
    }

    var FS = global.FS;

    var DataGrid = FS.Class.extend({

        init: function (options) {
            this.grid = null;
            $.extend(this, options);
        },

        setSearchQuery: function ( searchQuery ) {
            if ( typeof searchQuery !== 'undefined' ) {
                this.query['$where'] = searchQuery;
            }
            return this;
        },

        getGrid: function() {
            return this.grid;
        },

        render: function() {
            var _this = this;

            this.grid = $(this.container).dataTable( {
                processing: true,
                serverSide: true,
                searching: false,
                iDisplayLength: _this.ROWS_PER_PAGE,
                bLengthChange: false,
                order: [[ 2,'desc']],
                language: {
                    info: 'Showing _START_ to _END_ of _MAX_ records',
                    processing: '<div class="dataViewFetching"></div>'
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
                    var columnNames = ['descriptionofcontractrequirement','vendorname','piid','dollarsobligated','signeddate','agencyid','fundingrequestingagencyid'];
                    var order = columnNames[oSettings.aaSorting[0][0]+1] + ' ' + oSettings.aaSorting[0][1];

                    var query = {
                        '$select': columnNames.join(','),
                        '$order': order,
                        '$offset': page * _this.ROWS_PER_PAGE,
                        '$limit': _this.ROWS_PER_PAGE
                    };

                    if ( _this.query['$where'] ) {
                        query['$where'] = _this.query['$where'];
                    }

                    oSettings.jqXHR = $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: _this.api_host,
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
                                '$select': 'count(1) AS total'
                            };

                            if ( query['$where'] ) {
                                countQuery['$where'] = query['$where'];
                            }

                            $.ajax({
                                url: _this.api_host,
                                type: 'GET',
                                dataType: 'json',
                                data: countQuery
                            }).done(function( data ) {
                                json.iTotalDisplayRecords = data[0].total;
                                json.iTotalRecords = data[0].total;

                                fnCallback(json);

                                if ( typeof _this.doneCallback === 'function' ) {
                                    _this.doneCallback();
                                }

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

        }

    });

    FS.Visualization.DataGrid = DataGrid;

})(typeof window === 'undefined' ? this : window, jQuery);


