<img class="homepage-graphic" src="/sites/all/themes/nexus/img/homepage_process.png" width="859" height="256">
<div class="oddColumnContainer">
    <div class="row center-block">
        <div>
            <div id="searchInputKeywords" class="homepageSearchBar col-md-9 col-centered">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by keywords...">
                    <span class="input-group-btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> <span class="sr-only">Search</span></button></span>
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
                <br class="clearfloat">
            </div>
        </div>
    </div>
</div>