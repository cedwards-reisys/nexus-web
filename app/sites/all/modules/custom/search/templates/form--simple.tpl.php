<div id="homepageMainSection" class="col-md-12">
    <div class="row">
        <div id="homepageHeadline" class="col-md-5 col-md-offset-2">
            <h1>The Offical<br>Government Source</br>for Federal Spending Data</h1>
        </div>
        <div class="col-md-3">
            <div class="shim"></div>
            <div id="homepageSearch" class="input-group">
                <input type="text" class="form-control" placeholder="Search by keywords...">
                <span class="input-group-btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> <span class="sr-only">Search</span></button></span>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(function() {

            jQuery('#searchInputKeywords').find('button').on('click',function(){
                var Uri = new FS.Util.UriHandler('/search');
                var keywords = jQuery('#searchInputKeywords').find('input').val();

                Uri.addParam('keywords',keywords).redirect();
            });

            jQuery('#searchInputKeywords').find('input').on('keyup',function(e){
                var key = e.which;
                if ( key == 13 ) {
                    var Uri = new FS.Util.UriHandler('/search');
                    var keywords = jQuery('#searchInputKeywords').find('input').val();

                    Uri.addParam('keywords',keywords).redirect();
                    return false;
                }
            });

        });
    </script>
</div>
