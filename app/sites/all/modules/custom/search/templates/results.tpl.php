
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group" id="searchInputKeywords">
            <input type="text" class="form-control " placeholder="Start typing here to find agencies, vendors, and more...">
            <span class="input-group-btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> <span class="sr-only">Search</span></button></span>
        </div>
    </div>
</div>

<hr/>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <dl>
                    <dt>Total Amount</dt>
                    <dd>$3,076,121</dd>
                </dl>
            </div>
            <div class="col-md-4">
                <dl id="searchTransactionCount">
                    <dt>Transactions</dt>
                    <dd>20</dd>
                </dl>
            </div>
            <div class="col-md-4">
                <dl>
                    <dt>Contracts</dt>
                    <dd>10</dd>
                </dl>
            </div>
        </div>
    </div>
</div>


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

<script type="text/javascript">
    jQuery(function() {

        var searchResultsTable = jQuery('#searchResults').dataTable( {
            processing: true,
            ajax: {
                url: 'https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json?$select=vendorname,idvpiid,dollarsobligated,effectivedate,contractactiontype,maj_fund_agency_cat,fundingrequestingagencyid&$limit=20',
                dataSrc: ''
            },
            columns: [
                {
                    data: "vendorname"
                },
                {
                    data: "idvpiid"
                },
                {
                    data: "dollarsobligated"
                },
                {
                    data: "effectivedate"
                },
                {
                    data: "contractactiontype"
                },
                {
                    data: "maj_fund_agency_cat"
                },
                {
                    data: "fundingrequestingagencyid"
                }
            ]
        });


        jQuery.get('https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json?$select=count(1)', function( data ) {
            jQuery('#searchTransactionCount').find('dd').html(data[0].count_1);
        },'json');


        jQuery('#searchInputKeywords').find('button').on('click',function(){

            searchResultsTable.api().ajax.url('https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json?$limit=30');
            searchResultsTable.api().ajax.reload( function ( json ) {

                jQuery.get('https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json?$select=count(1)', function( data ) {
                    jQuery('#searchTransactionCount').find('dd').html(data[0].count_1);
                },'json');

            });


        });

    });



</script>