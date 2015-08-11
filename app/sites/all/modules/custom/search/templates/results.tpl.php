
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

        <div id="searchFilterPanel" class="panel-group" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Amount
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body" style="padding-left: 5px; padding-right: 5px;">

                        <form class="form-horizontal">
                            <div class="form-group form-group-sm">
                                <div class="col-md-7" style="padding-left: 5px; padding-right: 5px;">
                                    <select class="form-control input-sm">
                                        <option></option>
                                        <option value="<">Less than</option>
                                        <option value=">">Greater than</option>
                                    </select>
                                </div>
                                <div class="col-md-5" style="padding-left: 5px; padding-right: 5px;">
                                    <input type="text" class="form-control input-sm" id="exampleInputAmount" placeholder="Amount">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <div class="col-md-7" style="padding-left: 5px; padding-right: 5px;">
                                    <select class="form-control input-sm">
                                        <option></option>
                                        <option value="<">Less than</option>
                                        <option value=">">Greater than</option>
                                    </select>
                                </div>
                                <div class="col-md-5" style="padding-left: 5px; padding-right: 5px;">
                                    <input type="text" class="form-control input-sm" id="exampleInputAmount" placeholder="Amount">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Award ID
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <input type="text" id="awardIdInput" class="form-control" placeholder="Award ID">
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
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
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Recipient
                        </a>
                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                    <div class="panel-body">
                        <input type="text" id="recipientNameInput" class="form-control" placeholder="Recipient Name">
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFive">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Transaction Type
                        </a>
                    </h4>
                </div>
                <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                    <div class="panel-body">
                        <form id="awardTypeInput">
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingSix">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Contracting Agency
                        </a>
                    </h4>
                </div>
                <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                    <div class="panel-body">
                        <input type="text" id="contractAgencyNameInput" class="form-control" placeholder="Agency Name">
                    </div>
                </div>
            </div>
            
            
        </div>


    </div>

    <div class="col-md-9">
        <h3>Results</h3>

        <div id="searchMessageWrapper">
            <div id="searchErrorMessage" class="alert alert-danger alert-dismissible fade in" role="alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4>Uh oh! Something went wrong!</h4>
                <p>The search request could not be completed. Please choose from the following to continue searching:</p>
                <p>
                    <button type="button" class="btn btn-danger" onclick="location.reload(true);">Reload the page</button>
                    <button type="button" class="btn btn-default" onclick="jQuery('#searchErrorMessage').hide();">Or modify the search criteria</button>
                </p>
            </div>
        </div>

        <div id="searchTableWrapper">

            <table id="searchResults" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Award ID</th>
                    <th>Award Amount</th>
                    <th>Award Date</th>
                    <th>Transaction Type</th>
                    <th>Contracting Agency</th>
                    <th>Funding Agency</th>
                </tr>
                </thead>
            </table>

        </div>

    </div>

    <div class="col-md-1 dataViewButtons">
        <div><button class="btn btn-primary active"><span class="glyphicon glyphicon-th"></span></button></div>
        <div><button class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon glyphicon-signal"></span></button></div>
        <div><button class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon glyphicon-globe"></span></button></div>
    </div>

</div>


