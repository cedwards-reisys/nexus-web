
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="input-group" id="searchInputKeywords">
            <input type="text" class="form-control " placeholder="Start typing here to find agencies, vendors, and more...">
            <span class="input-group-btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> <span class="sr-only">Search</span></button></span>
        </div>
    </div>
</div>

<h3>Keywords: <span id="keywordsText"></span> </h3>

<div class="panel panel-default">
    <div class="panel-body">
        <h3>Summary</h3>
        <div class="row">
            <div class="col-md-4">
                <dl id="searchTransactionSum">
                    <dt>Total Amount</dt>
                    <dd>0</dd>
                </dl>
            </div>
            <div class="col-md-4">
                <dl id="searchTransactionCount">
                    <dt>Transactions</dt>
                    <dd>0</dd>
                </dl>
            </div>
            <div class="col-md-4">
                <dl id="searchContractCount">
                    <dt>Contracts</dt>
                    <dd>0</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-2">
        <h3>Filter</h3>

        <div id="searchFilterPanel" class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Amount
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">

                        <form class="form-inline">
                            <div class="form-group">
                                <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
                                    <div class="input-group-addon">.00</div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Award ID
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">

                        <input type="text" class="form-control" placeholder="Award ID">

                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Award Date
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">

                        <form class="form-inline">
                            <div class="form-group">
                                <label for="exampleInputName2">From</label>
                                <input type="text" class="form-control" id="exampleInputName2" placeholder="mm/dd/yyy">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail2">To</label>
                                <input type="email" class="form-control" id="exampleInputEmail2" placeholder="mm/dd/yyyy">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFour">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Recipient
                        </a>
                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                    <div class="panel-body">

                        <input type="text" class="form-control" placeholder="Recipient Name">

                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFive">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Award Type
                        </a>
                    </h4>
                </div>
                <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                    <div class="panel-body">

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                DO Delivery Order
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                PO Purchase Order
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                DCA Definitive Contract
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="col-md-9">
        <h3>Results</h3>

        <table id="searchResults" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Recipient</th>
                <th>Award ID</th>
                <th>Award Amount</th>
                <th>Award Date</th>
                <th>Award Type</th>
                <th>Awarding Agency</th>
                <th>Funding Agency</th>
            </tr>
            </thead>
        </table>

    </div>

    <div class="col-md-1 dataViewButtons">
        <div><button class="btn btn-primary active"><span class="glyphicon glyphicon-th"></span></button></div>
        <div><button class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon glyphicon-signal"></span></button></div>
        <div><button class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon glyphicon-globe"></span></button></div>
    </div>

</div>





<script type="text/javascript">
    jQuery(function() {

        var API_HOST = 'https://fedspending.demo.socrata.com/resource/nfu7-rhaq.json';

        var Uri = new FS.Util.UriHandler();
        jQuery('#searchInputKeywords').find('input').val(Uri.getParam('keywords'));

        var ROWS_PER_PAGE = 20;

        var searchResultsTable = jQuery('#searchResults').dataTable( {
            processing: true,
            serverSide: true,
            searching: false,
            iDisplayLength: ROWS_PER_PAGE,
            //pageLength: ROWS_PER_PAGE,
            bLengthChange: false,
            order: [[ 3,'desc']],
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
                    name: 'piid'
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
            fnServerData: function  ( sSource, aoData, fnCallback, oSettings ) {
                var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
                var columnNames = ['vendorname','piid','dollarsobligated','signeddate','contractactiontype','agencyid','fundingrequestingagencyid'];
                var order = columnNames[oSettings.aaSorting[0][0]] + ' ' + oSettings.aaSorting[0][1];

                jQuery('#searchTransactionCount').find('dd').html('<div class="dataFetching"></div>');
                jQuery('#searchContractCount').find('dd').html('<div class="dataFetching"></div>');
                jQuery('#searchTransactionSum').find('dd').html('<div class="dataFetching"></div>');

                var query = {
                    '$select': columnNames.join(','),
                    '$order': order,
                    '$offset': page * ROWS_PER_PAGE,
                    '$limit': ROWS_PER_PAGE
                };

                var textSearch = jQuery('#searchInputKeywords').find('input').val();
                if ( textSearch ) {

                    jQuery('#keywordsText').html(textSearch);

                    //query['$q'] = textSearch;
                    query['$where'] = 'UPPER(vendorname) like \'%' + textSearch.replace(/'/g, "''").toUpperCase() + '%\'';
                    query['$where'] += ' OR UPPER(contractactiontype) like \'%' + textSearch.replace(/'/g, "''").toUpperCase() + '%\'';
                    query['$where'] += ' OR UPPER(agencyid) like \'%' + textSearch.replace(/'/g, "''").toUpperCase() + '%\''
                    query['$where'] += ' OR UPPER(fundingrequestingagencyid) like \'%' + textSearch.replace(/'/g, "''").toUpperCase() + '%\''
                }

                oSettings.jqXHR = jQuery.ajax({
                    dataType: 'json',
                    type: 'GET',
                    url: API_HOST,
                    data: query
                }).done(function (data) {
                    var json = {};
                    if ( typeof data['error'] === 'undefined' ) {

                        json.iTotalDisplayRecords = ROWS_PER_PAGE;
                        for ( var i = 0, ilen = data.length; i < ilen; i++ ) {
                            for ( var j = 0, jlen = columnNames.length; j < jlen; j++ ) {
                                if ( typeof data[i][columnNames[j]] == 'undefined' ) {
                                    data[i][columnNames[j]] = '';
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

                        jQuery.ajax({
                            url: API_HOST,
                            type: 'GET',
                            dataType: 'json',
                            data: countQuery
                        }).done(function( data ) {
                            jQuery('#searchTransactionCount').find('dd').html(FS.Util.NumberFormat.getString(data[0].count_1, 0));
                            jQuery('#searchContractCount').find('dd').html(FS.Util.NumberFormat.getString(data[0].count_1, 0));

                            json.iTotalDisplayRecords = data[0].count_1;
                            json.iTotalRecords = data[0].count_1;
                            fnCallback(json);
                        }).fail(function(){
                            jQuery('#searchTransactionCount').find('dd').html('-');
                            jQuery('#searchContractCount').find('dd').html('-');
                        });

                        var sumQuery = {
                            '$select': 'sum(dollarsobligated)'
                        };

                        if ( query['$where'] ) {
                            sumQuery['$where'] = query['$where'];
                        }
                        jQuery.ajax({
                            url: API_HOST,
                            type: 'GET',
                            dataType: 'json',
                            data: sumQuery
                        }).done(function( data ) {
                            jQuery('#searchTransactionSum').find('dd').html(FS.Util.NumberFormat.getCurrency(data[0].sum_dollarsobligated,0));
                        }).fail(function(){
                            jQuery('#searchTransactionSum').find('dd').html('-');
                        });


                    } else {

                        debugger;

                        jQuery('#searchResults').dataTable().fnSettings().oLanguage.sEmptyTable = 'There was an error retrieving table data.';
                        jQuery('#searchResults').dataTable().fnDraw();

                        jQuery('#searchTransactionCount').find('dd').html('-');
                        jQuery('#searchContractCount').find('dd').html('-');
                        jQuery('#searchTransactionSum').find('dd').html('-');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    debugger;

                    jQuery('#searchResults').dataTable().fnSettings().oLanguage.sEmptyTable = 'There was an error retrieving table data.';
                    jQuery('#searchResults').dataTable().fnDraw();

                    jQuery('#searchTransactionCount').find('dd').html('-');
                    jQuery('#searchContractCount').find('dd').html('-');
                    jQuery('#searchTransactionSum').find('dd').html('-');

                    //fnCallback({aaData:[],iTotalDisplayRecords:ROWS_PER_PAGE});
                });
            }
        });


        jQuery('#searchInputKeywords').find('button').on('click',function(){
            searchResultsTable.api().ajax.reload();
        });

        jQuery('#searchInputKeywords').find('input').on('keyup',function(e){
            var key = e.which;
            if ( key == 13 ) {
                searchResultsTable.api().ajax.reload();
                return false;
            }
        });

    });



</script>