(function(global, $, undefined){

    if (typeof global.FS === 'undefined') {
        throw new Error('SearchApp requires FS');
    }

    var FS = global.FS;

    var SearchApp = FS.Class.extend({

        init: function () {
            this.API_HOST = 'https://fedspending.demo.socrata.com/resource/nfu7-rhaq.json';
            this.ROWS_PER_PAGE = 20;
            this.query = {};
        },

        run: function() {
            this.registerComponents();
            this.getDataViewPanels();
            this.addFilterFormHandlers();

            var _this = this;
            var Uri = new FS.Util.UriHandler();
            $('#searchInputKeywords').find('input').val(Uri.getParam('keywords'));

            this.applySearchFilters();

            $('.dataViewButtons').find('button').on('click',function(){

                $('.dataViewButtons').find('button').removeClass('btn-primary active').addClass('btn-default');
                $(this).addClass('btn-primary active');

                var selectedPanel = $(this).data('panel');

                $.each(_this.getDataViewPanels(),function(k,panel){
                    if ( k === selectedPanel ) {
                        panel.wrapper.show();
                        _this.applySearchFilters();
                        panel.render();
                    } else {
                        panel.wrapper.hide();
                    }
                });
            });

            this.getDataViewPanels()['grid'].render();
        },

        registerComponents: function () {
            var _this = this;
            this.components = {};

            this.components['totalAmount'] = new FS.Visualization.TotalAmount({
                container: '#searchTransactionSum',
                api_host: this.API_HOST,
                query: {
                    '$select': 'sum(dollarsobligated) AS total'
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['totalTransactions'] = new FS.Visualization.TotalTransactions({
                container: '#searchTransactionCount',
                api_host: this.API_HOST,
                query: {
                    '$select': 'count(1) AS total'
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['dataGrid'] = new FS.Visualization.DataGrid({
                container: '#searchResults',
                api_host: this.API_HOST,
                query: {
                },
                ROWS_PER_PAGE: _this.ROWS_PER_PAGE,
                doneCallback: function() {
                    _this.components['totalAmount'].render();
                    _this.components['totalTransactions'].render();
                },
                failCallback: function() {}
            });

            this.components['agencyBarChart'] = new FS.Visualization.BarChart({
                container: '#barChartAgency svg',
                api_host: this.API_HOST,
                query: {
                    '$select': 'agencyid AS x, SUM(dollarsobligated) AS y',
                    '$group': 'x',
                    '$order': 'y DESC',
                    '$limit': 10
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['vendorBarChart'] = new FS.Visualization.BarChart({
                container: '#barChartVendor svg',
                api_host: this.API_HOST,
                query: {
                    '$select': 'vendorname AS x, SUM(dollarsobligated) AS y',
                    '$group': 'x',
                    '$order': 'y DESC',
                    '$limit': 10
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['productBarChart'] = new FS.Visualization.BarChart({
                container: '#barChartProduct svg',
                api_host: this.API_HOST,
                query: {
                    '$select': 'productorservicecode AS x, SUM(dollarsobligated) AS y',
                    '$group': 'x',
                    '$order': 'y DESC',
                    '$limit': 10
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['naicsBarChart'] = new FS.Visualization.BarChart({
                container: '#barChartNaics svg',
                api_host: this.API_HOST,
                query: {
                    '$select': 'principalnaicscode AS x, SUM(dollarsobligated) AS y',
                    '$group': 'x',
                    '$order': 'y DESC',
                    '$limit': 10
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['vendorUsMap'] = new FS.Visualization.Choropleth({
                container: 'mapUsVendor',
                api_host: this.API_HOST,
                query: {
                    '$select': 'vendor_state_code AS x, SUM(dollarsobligated) AS y',
                    '$group': 'x',
                    '$where': '(vendor_state_code IS NOT NULL AND vendorcountrycode = \'UNITED STATES\')'
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['popUsMap'] = new FS.Visualization.Choropleth({
                container: 'mapUsPop',
                api_host: this.API_HOST,
                query: {
                    '$select': 'pop_state_code AS x, SUM(dollarsobligated) AS y',
                    '$group': 'x',
                    '$where': '(pop_state_code IS NOT NULL AND pop_state_code != \': \' AND placeofperformancecountrycode = \'USA: UNITED STATES\')'
                },
                doneCallback: function() {},
                failCallback: function() {}
            });

            this.components['amountTimeSeries'] = new FS.Visualization.TimeSeries({
                container: '#timeSeriesAmount svg',
                api_host: this.API_HOST,
                query: {
                    '$select': 'sum(dollarsobligated) AS amount, date_trunc_ym(signeddate) AS date',
                    '$group': 'date',
                    '$where': 'signeddate IS NOT NULL',
                    '$order': 'date ASC'
                },
                doneCallback: function() {},
                failCallback: function() {}
            });
        },

        getComponent: function( name ) {
            return this.components[name];
        },

        getDataViewPanels: function() {
            if ( !this.dataViewPanels ) {
                var _this = this;
                this.dataViewPanels = {
                    grid: {
                        wrapper: $('#searchTableWrapper'),
                        render: function () {
                            _this.getComponent('dataGrid').setSearchQuery(_this.query['$where']);
                            if ( !_this.getComponent('dataGrid').getGrid() ) {
                                _this.getComponent('dataGrid').render();
                            } else {
                                _this.getComponent('dataGrid').getGrid().api().ajax.reload();
                            }
                        }
                    },
                    bar: {
                        wrapper: $('#searchBarChartWrapper'),
                        render: function () {
                            _this.getComponent('agencyBarChart').setSearchQuery(_this.query['$where']).render();
                            _this.getComponent('agencyBarChart').setSearchQuery(_this.query['$where']).render();
                            _this.getComponent('productBarChart').setSearchQuery(_this.query['$where']).render();
                            _this.getComponent('naicsBarChart').setSearchQuery(_this.query['$where']).render();
                        }
                    },
                    map: {
                        wrapper: $('#searchMapWrapper'),
                        render: function () {
                            _this.getComponent('vendorUsMap').setSearchQuery(_this.query['$where']).render();
                            _this.getComponent('popUsMap').setSearchQuery(_this.query['$where']).render();
                        }
                    },
                    time: {
                        wrapper: $('#searchTimeWrapper'),
                        render: function () {
                            _this.getComponent('amountTimeSeries').setSearchQuery(_this.query['$where']).render();
                        }
                    }
                };
            }
            return this.dataViewPanels;
        },

        applySearchFilters: function () {

            // Keyword Input
            var textSearch = $('#searchInputKeywords').find('input').val();
            if ( textSearch ) {
                $('#keywordsText').html(textSearch);

                var preparedTextSearch = textSearch.replace(/'/g, "''").toUpperCase();
                var textFields = ['vendorname','agencyid','fundingrequestingagencyid','descriptionofcontractrequirement'];
                var textQueries = [];
                for ( var i= 0,len=textFields.length;i<len;i++) {
                    textQueries.push('UPPER(' + textFields[i] + ') like \'%' + preparedTextSearch + '%\'');
                }
                this.query['$where'] = '(' + textQueries.join(' OR ') + ') ';
            }

            // Award Amount
            var awardAmountSearch = $('#awardAmountInput').val();
            var awardAmountOperatorSearch = $('#awardAmountOperatorInput').val();

            if ( awardAmountSearch && awardAmountOperatorSearch ) {
                if ( typeof this.query['$where'] === 'undefined' ) {
                    this.query['$where'] = '';
                } else {
                    this.query['$where'] += ' AND ';
                }
                this.query['$where'] += 'dollarsobligated ' + awardAmountOperatorSearch + ' ' + parseInt(awardAmountSearch) + ' ';
            }

            // Award Id Input
            var awardIdSearch = $('#awardIdInput').val();
            if ( awardIdSearch ) {
                if ( typeof this.query['$where'] === 'undefined' ) {
                    this.query['$where'] = '';
                } else {
                    this.query['$where'] += ' AND ';
                }
                this.query['$where'] += 'UPPER(piid) = \''+awardIdSearch.toUpperCase()+'\' ';
            }

            // Recipient Name Input
            var recipientNameSearch = $('#recipientNameInput').val();
            if ( recipientNameSearch ) {
                if ( typeof this.query['$where'] === 'undefined' ) {
                    this.query['$where'] = '';
                } else {
                    this.query['$where'] += ' AND ';
                }
                this.query['$where'] += 'UPPER(vendorname) = \''+recipientNameSearch.toUpperCase()+'\' ';
            }

            // Contracting Agency Name Input
            var contractAgencyNameSearch = $('#contractAgencyNameInput').val();
            if ( contractAgencyNameSearch ) {
                if ( typeof this.query['$where'] === 'undefined' ) {
                    this.query['$where'] = '';
                } else {
                    this.query['$where'] += ' AND ';
                }
                this.query['$where'] += 'UPPER(agencyid) = \''+contractAgencyNameSearch.toUpperCase()+'\' ';
            }

        },

        updateDataView: function() {
            this.applySearchFilters();

            var selectedPanel = $('.dataViewButtons').find('button.active').data('panel');
            $.each(this.getDataViewPanels(),function(k,panel){
                if ( panel === selectedPanel ) {
                    panel.render();
                }
            });

            this.getComponent('totalAmount').render();
            this.getComponent('totalTransactions').render();
        },

        addFilterFormHandlers: function() {
            var _this = this;

            $('#searchInputKeywords').find('button').on('click',function(){
                _this.updateDataView();
            });

            $('#searchInputKeywords').find('input').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 ) {
                    _this.updateDataView();
                    return false;
                }
            });

            $('#awardIdInput').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 && $(this).val() ) {
                    _this.updateDataView();
                    return false;
                }
            });

            $('#recipientNameInput').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 && $(this).val() ) {
                    _this.updateDataView();
                    return false;
                }
            });

            // Amount form controller
            $('#awardAmountInput').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 && $(this).val() ) {
                    _this.updateDataView();
                    return false;
                }
            });

            $('#awardAmountOperatorInput').on('change',function(e){
                if ( $('#awardAmountInput').val() ) {
                    _this.updateDataView();
                }
            });

            $('#contractAgencyNameInput').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 && $(this).val() ) {
                    _this.updateDataView();
                    return false;
                }
            });
        }
    });

    FS.SearchApp = new SearchApp();

    FS.SearchApp.run();

})(typeof window === 'undefined' ? this : window, jQuery);
