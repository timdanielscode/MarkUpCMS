var button = document.getElementById('codeEditorFullScreen');
var editor = document.querySelector('.CodeMirror');

button.addEventListener("click", function() { 
 
    var body = document.querySelector('body');

    editor.classList.add("fullscreen");
    body.append(editor);
}); 

window.addEventListener("keyup", function(event) { 

    if(event.key === "Escape") {

        editor.classList.remove("fullscreen");

        var form = document.getElementById('editorForm');
        form.append(editor);
    }
}); 