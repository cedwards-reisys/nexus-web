
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="input-group" id="searchInputKeywords">
            <input type="text" class="form-control " placeholder="Start typing here to find agencies, vendors, and more...">
            <span class="input-group-btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> <span class="sr-only">Search</span></button></span>
        </div>
    </div>
</div>

<h2>Keywords: <span id="keywordsText">"treasury"</span> </h2>

<div class="panel panel-default">
    <div class="panel-body">
        <h3>Results Summary</h3>
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
        <h3>Show Results with:</h3>


    </div>

    <div class="col-md-10">
        <h3>Results Analysis</h3>

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

</div>

<script type="text/javascript">
    jQuery(function() {


        var Uri = new FS.Util.UriHandler();
        jQuery('#searchInputKeywords').find('input').val(Uri.getParam('keywords'));

        var ROWS_PER_PAGE = 20;

        var searchResultsTable = jQuery('#searchResults').dataTable( {
            processing: true,
            serverSide: true,
            searching: false,
            pageLength: ROWS_PER_PAGE,
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

                    query['$q'] = textSearch;
                    //query['$where'] = 'vendorname like \'%' + textSearch.replace(/'/g, "''") + '%\'';
                    //query['$where'] += ' OR contractactiontype like \'%' + textSearch.replace(/'/g, "''") + '%\'';
                    //query['$where'] += ' OR agencyid like \'%' + textSearch.replace(/'/g, "''") + '%\''
                    //query['$where'] += ' OR fundingrequestingagencyid like \'%' + textSearch.replace(/'/g, "''") + '%\''
                }

                oSettings.jqXHR = jQuery.ajax({
                    dataType: 'json',
                    type: 'GET',
                    url: 'https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json',
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

                        jQuery.ajax({
                            url: 'https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json?$select=count(1)',
                            dataType: 'json'
                        }).done(function( data ) {
                            jQuery('#searchTransactionCount').find('dd').html(FS.Util.NumberFormat.getString(data[0].count_1, 0));
                            jQuery('#searchContractCount').find('dd').html(FS.Util.NumberFormat.getString(data[0].count_1, 0));

                            json.iTotalRecords = data[0].count_1;
                            fnCallback(json);
                        }).fail(function(){
                            jQuery('#searchTransactionCount').find('dd').html('-');
                            jQuery('#searchContractCount').find('dd').html('-');
                        });

                        jQuery.ajax({
                            url: 'https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json?$select=sum(dollarsobligated)',
                            dataType: 'json'
                        }).done(function( data ) {
                            jQuery('#searchTransactionSum').find('dd').html(FS.Util.NumberFormat.getCurrency(data[0].sum_dollarsobligated,0));
                            json.iTotalRecords = data[0].count_1;
                            fnCallback(json);
                        }).fail(function(){
                            jQuery('#searchTransactionSum').find('dd').html('-');
                        });


                    } else {

                        debugger;




                        jQuery('#searchResults').dataTable().fnSettings().oLanguage.sEmptyTable = 'There was an error retrieving table data.';
                        jQuery('#searchResults').dataTable().fnDraw();
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    debugger;
                    jQuery('#searchTransactionCount').find('dd').html('-');
                    jQuery('#searchContractCount').find('dd').html('-');
                    jQuery('#searchTransactionSum').find('dd').html('-');
                    fnCallback({aaData:[],iTotalDisplayRecords:ROWS_PER_PAGE});
                });
            }
        });


        jQuery('#searchInputKeywords').find('button').on('click',function(){
            searchResultsTable.api().ajax.reload();
        });

    });



</script>