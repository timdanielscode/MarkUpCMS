$(document).on('click', '#codeEditorFullScreen', function() {
    $(".CodeMirror").addClass("fullscreen");
});
$(document).keyup(function(e) {
    if (e.key === "Escape") { 
        $(".CodeMirror").removeClass("fullscreen");
    }
});