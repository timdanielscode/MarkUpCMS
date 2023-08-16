$(document).on('click', '#codeEditorFullScreen', function() {
    
    $(".CodeMirror").addClass("fullscreen");
    $($("body")).append($(".CodeMirror"))
});
$(document).keyup(function(e) {
    if (e.key === "Escape") { 
        $(".CodeMirror").removeClass("fullscreen");
        $($("#editorForm")).append($(".CodeMirror"))
    }
});