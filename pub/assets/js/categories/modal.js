$(document).on('click', '#BACK', function() {
    
    var modal = $('#modal');
    modal.removeClass('display-block'); 
    var html = $('html');
    html.removeClass('dark-layer');
});