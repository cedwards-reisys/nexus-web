(function(global, $){

    var leavingSiteModal = $([
        '<div class="modal fade leaving-site-modal" tabindex="-1" role="dialog" aria-labelledby="leavingSiteModalLabel">',
        '   <div class="modal-dialog modal-sm">',
        '       <div class="modal-content panel-warning">',
        '           <div class="modal-header panel-heading">',
        '               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
        '               <h4 class="modal-title" id="leavingSiteModalLabel"><span class="glyphicon glyphicon-warning-sign"></span> Warning! Leaving FedSpending.gov</h4>',
        '           </div>',
        '           <div class="modal-body">',
        '               <p>You have initiated an action that will redirect your browser to an Non Federal Government domain.</p>',
        '               <p>By choosing to continue, you are acknowledging your understanding of all consequences and accept all risks.</p>',
        '           </div>',
        '           <div class="modal-footer">',
        '               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>',
        '               <button type="button" class="btn btn-danger" data-continue="external">Continue</button>',
        '           </div>',
        '       </div>',
        '   </div>',
        '</div>'
    ].join('\n'));

    $('a[data-notify=\'external\']').on('click',function(event){
        event.stopPropagation();
        event.preventDefault();

        leavingSiteModal.modal('show');

        var externalURI = $(this).attr('href');
        leavingSiteModal.find('button[data-continue=\'external\']').off('click').on('click', function(){
            leavingSiteModal.modal('hide');
            window.open(externalURI);
        });
    });

})(typeof window === 'undefined' ? this : window, jQuery);
