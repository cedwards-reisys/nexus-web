<!-- SEARCH BAR -->
<div class="col-md-3 col-md-offset-8 input-group" id="searchInputKeywords">
    <input type="text" class="form-control" placeholder="Search by keywords...">
    <span class="input-group-btn">
        <button type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            <span class="sr-only">Search</span>
        </button>
    </span>
</div>
<div class="row">
    <!-- KEYWORD DISPLAY -->
    <div class="col-md-10 col-md-offset-1">
        <h5 id="searchKeywords"><span>Keywords:</span> <span id="keywordsText"></span></h5>
        <div id="resultsSummary">
            <div class="row">
                <div class="col-md-3">
                    <dl id="searchTransactionSum">
                        <dt style="font-weight:normal;">Total Amount</dt>
                        <dd style="font-size:21px;font-weight:bold;">0</dd>
                    </dl>
                </div>
                <div class="col-md-3">
                    <dl id="searchTransactionCount">
                        <dt style="font-weight:normal;">Contracts</dt>
                        <dd style="font-size:21px;font-weight:bold;">0</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- FILTERS -->
    <div id="searchFilterSection" class="col-md-2 col-md-offset-1">
        <h4>Filter</h4>
        <div id="searchFilterPanel" class="panel-group" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading filterHeader" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Amount
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body" style="padding-left: 5px; padding-right: 5px;">
                        <div class="form-group form-group-sm">
                            <div class="col-md-7" style="padding-left: 5px; padding-right: 5px;">
                                <select class="form-control input-sm" id="awardAmountOperatorInput">
                                    <option value="<">Less than</option>
                                    <option value=">">Greater than</option>
                                </select>
                            </div>
                            <div class="col-md-5" style="padding-left: 5px; padding-right: 5px;">
                                <input type="text" class="form-control input-sm" id="awardAmountInput" placeholder="Amount">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading filterHeader" role="tab" id="headingTwo">
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
                <div class="panel-heading filterHeader" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#searchFilterPanel" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Award Date
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">
                        <label for="awardDateFromInput">Between</label>
                        <input type="text" class="form-control input-sm" id="awardDateFromInput" placeholder="mm/dd/yyy">

                        <label for="awardDateToInput">And</label>
                        <input type="email" class="form-control input-sm" id="awardDateToInput" placeholder="mm/dd/yyyy">
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading filterHeader" role="tab" id="headingFour">
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
                <div class="panel-heading filterHeader" role="tab" id="headingSix">
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
        <div id="filterControls">
            <button id="filterControlApply" class="btn btn-default btn-xs btn-submit floatleft">Apply</button>
            <button id="filterControlClear" class="btn btn-default btn-xs btn-cancel floatright">Clear</button>
        </div>
    </div>
    <!-- RESULTS -->
    <div id="searchResultSection" class="col-md-7">
        <h4>Results</h4>
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
                    <th>Contracting Agency</th>
                    <th>Funding Agency</th>
                </tr>
                </thead>
            </table>
        </div>
        <div id="searchBarChartWrapper" style="display: none;">
            <h4>Top Award Amounts</h4>
            <div class="row">
                <div class="col-md-6">
                    <h5>Contracting Agencies</h5>
                    <div id="barChartAgency">
                        <svg></svg>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Recipients/Sub-recipients</h5>
                    <div id="barChartVendor">
                        <svg></svg>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Product/Service Codes</h5>
                    <div id="barChartProduct">
                        <svg></svg>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>NAICS Codes</h5>
                    <div id="barChartNaics">
                        <svg></svg>
                    </div>
                </div>
            </div>
        </div>
        <div id="searchMapWrapper" style="display: none;">
            <h4>Top Award Amounts</h4>
            <div class="row">
                <div class="col-md-6">
                    <h5>Recipient Location</h5>
                    <div id="mapUsVendor"></div>
                </div>
                <div class="col-md-6">
                    <h5>Place of Performance</h5>
                    <div id="mapUsPop"></div>
                </div>
            </div>
        </div>
        <div id="searchTimeWrapper" style="display: none;">
            <h4>Award Amounts Per Month</h4>
            <div class="row">
                <div class="col-md-12">
                    <div id="timeSeriesAmount"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- VISUALIZATION -->
    <div class="col-md-1">
        <div id="dataViewButtons">
            <div><button data-panel="grid" class="btn btn-primary active"><span class="glyphicon glyphicon-th"></span></button></div>
            <div><button data-panel="bar" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-signal"></span></button></div>
            <div><button data-panel="map" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-globe"></span></button></div>
            <div><button data-panel="time" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-time"></span></button></div>
        </div>
    </div>
</div>
