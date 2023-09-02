$(document).ready(function() {

    var editor = document.querySelector('.CodeMirror');
    editor.style.fontSize = "13px";
    var currentSize = editor.style.fontSize.replace('px','');

    $(document).on('click', '#codeEditorZoomIn', function() {
        
        editor.style.fontSize = currentSize++ + "px";
    });

    $(document).on('click', '#codeEditorZoomOut', function() {
        
        editor.style.fontSize = currentSize-- + "px";
    });
});