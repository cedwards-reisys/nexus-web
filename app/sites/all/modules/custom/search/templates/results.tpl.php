
<div class="row">
    <div class="col-md-3">
        Total Amount
        <strong>$3,076,</strong>
    </div>
    <div class="col-md-3">

    </div>
    <div class="col-md-3">

    </div>
    <div class="col-md-3">

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
        jQuery('#searchResults').dataTable( {
            "processing": true,
            "ajax": {
                "url": "https://fedspending.demo.socrata.com/resource/3kp6-u7ur.json?$limit=20",
                "dataSrc": ""
            },
            "columns": [
                {
                    "data": "vendorname"
                },
                {
                    "data": "idvpiid"
                },
                {
                    "data": "dollarsobligated"
                },
                {
                    "data": "effectivedate"
                },
                {
                    "data": "contractactiontype"
                },
                {
                    "data": "maj_fund_agency_cat"
                },
                {
                    "data": "fundingrequestingagencyid"
                }
            ]
        });
    });



</script>