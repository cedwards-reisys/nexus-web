
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="input-group" id="searchInputKeywords">
            <input type="text" class="form-control " placeholder="Start typing here to find agencies, vendors, and more...">
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