var editor = document.querySelector('.CodeMirror');
editor.style.fontSize = "13px";
var currentSize = editor.style.fontSize.replace('px','');

var zoomInButton = document.getElementById('codeEditorZoomIn');
var zoomOutButton = document.getElementById('codeEditorZoomOut');

zoomInButton.addEventListener("click", function() { 

    editor.style.fontSize = currentSize++ + "px";
}); 

zoomOutButton.addEventListener("click", function() { 

    editor.style.fontSize = currentSize-- + "px";
}); 