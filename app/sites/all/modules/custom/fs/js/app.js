(function(global, $){

    var leavingSiteModal = $([
        '<div class="modal leaving-site-modal" tabindex="-1" role="dialog" aria-labelledby="leavingSiteModalLabel">',
        '   <div class="modal-dialog modal-sm">',
        '       <div class="modal-content panel-success">',
        '           <div class="modal-header panel-heading">',
        '               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
        '               <h4 class="modal-title" id="leavingSiteModalLabel"><span class="glyphicon glyphicon-warning-sign"></span> You are leaving FedSpending.gov</h4>',
        '           </div>',
        '           <div class="modal-body">',
        '               <h3>YOU ARE LEAVING THE FEDSPENDING.GOV WEBSITE</h3>',
        '           </div>',
        '           <div class="modal-footer">',
        '               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>',
        '               <button type="button" class="btn btn-success" data-continue="external">Continue</button>',
        '           </div>',
        '       </div>',
        '   </div>',
        '</div>'
    ].join('\n'));

    $('a[data-notify="external"]').on('click',function(event){
        event.stopPropagation();
        event.preventDefault();

        var existingModal = $('.modal.in');

        if ( existingModal ) {
            existingModal.on('hidden.bs.modal', function (e) {
                leavingSiteModal.modal('show');
            });
            existingModal.modal('hide');
        } else {
            leavingSiteModal.modal('show');
        }

        var externalURI = $(this).attr('href');
        leavingSiteModal.find('button[data-continue=\'external\']').off('click').on('click', function(){
            leavingSiteModal.modal('hide');
            window.open(externalURI);
        });
    });

})(typeof window === 'undefined' ? this : window, jQuery);
