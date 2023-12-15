class Editor {

    constructor() {

        this.setKeyUpEvent(this);

        this.size = 13;
        this.getElement().style.fontSize = "13px";
    }

    /*
     * To get necessary elements
    */
    getElement() {

        return document.querySelector('.CodeMirror');
    }

    getBodyElement() {

        return document.querySelector('body');
    }

    getFormElement() {

        return document.getElementById('editorForm');
    }

    /*
     * To exit out from fullscreen mode after pressing the esc key
     *
     * @param object editor Editor
    */
    setKeyUpEvent(editor) {

        window.addEventListener("keyup", function(event) { 

            if(event.key === "Escape") {
        
                editor.getElement().classList.remove("fullscreen");
                editor.getFormElement().append(editor.getElement());
            }
        }); 
    }
}