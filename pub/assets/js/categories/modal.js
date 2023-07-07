$(document).on('click', '#mediaModalClose', function() {
    
    var modal = $('#modal');
    modal.removeClass('display-block'); 
    var html = $('html');
    html.removeClass('dark-layer');
});