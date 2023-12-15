class Button {

    constructor(section) {

        this.section = section;
    }

    /*
     * To get necessary elements
    */
    getMetaElement() {

        return document.getElementById('metaButton');
    }

    getCdnElement() {

        return document.getElementById('cdnButton');
    }

    getCategoryElement() {

        return document.getElementById('categoryButton');
    }

    getCssElement() {

        return document.getElementById('cssButton');
    }

    getJsElement() {

        return document.getElementById('jsButton');
    }

    getWidgetElement() {

        return document.getElementById('widgetButton');
    }

    getSlugElement() {

        return document.getElementById('slugButton');
    }

    getFullscreenElement() {

        return document.getElementById('codeEditorFullScreen');
    }

    getZoomInElement() {

        return document.getElementById('codeEditorZoomIn');
    }

    getZoomOutElement() {

        return document.getElementById('codeEditorZoomOut');
    }

    /*
     * After clicking on 'post buttons' to run the showHideSection method
     *
     * @param element object anchor tag (post button)
    */
    setOnclickEventPostEdit(element) {

        if(element !== null) {

            var button = this;

            element.addEventListener("click", function() { 
                    
                button.showHideSection(this);
            }); 
        }
    }

    /*
     * To show/hide post sections
     *
     * @param object element anchor tag (buttons)
    */
    showHideSection(element) {

        switch (element.id) {

            case 'slugButton':

                this.hideElements('slugButton');
                this.section.getSlugElement().classList.toggle('display-none');
            break;
            case 'categoryButton':

                this.hideElements('categoryButton');
                this.section.getCategoryElement().classList.toggle('display-none');
            break;
            case 'metaButton':

                this.hideElements('metaButton');
                this.section.getMetaElement().classList.toggle('display-none');
            break;
            case 'cdnButton':

                this.hideElements('cdnButton');
                this.section.getCdnElement().classList.toggle('display-none');
            break;
            case 'jsButton':

                this.hideElements('jsButton');
                this.section.getJsElements()[0].classList.toggle('display-none');
                this.section.getJsElements()[1].classList.toggle('display-none');
            break;
            case 'cssButton':

                this.hideElements('cssButton');
                this.section.getCssElements()[0].classList.toggle('display-none');
                this.section.getCssElements()[1].classList.toggle('display-none');
            break;
            case 'widgetButton':

                this.hideElements('widgetButton');
                this.section.getWidgetElement().classList.toggle('display-none');
            break;
        }
    }

    /*
     * To hide post sections
     *
     * @param string id anchor tag (button)
    */
    hideElements(id) {

        if(id !== this.section.getSlugElement().id) {

            this.section.getSlugElement().classList.add('display-none');
        }

        if(id !== this.section.getMetaElement().id) {

            this.section.getMetaElement().classList.add('display-none');
        }

        if(id !== this.section.getCategoryElement().id) {

            this.section.getCategoryElement().classList.add('display-none');
        }

        if(id !== this.section.getWidgetElement().id) {

            this.section.getWidgetElement().classList.add('display-none');
        }

        if(id !== this.section.getCdnElement().id) {

            this.section.getCdnElement().classList.add('display-none');
        }

        if(id !== this.section.getCssElements()[0].id) {

            this.section.getCssElements()[0].classList.add('display-none');
        }

        if(id !== this.section.getCssElements()[1].id) {

            this.section.getCssElements()[1].classList.add('display-none');
        }

        if(id !== this.section.getJsElements()[0].id) {

            this.section.getJsElements()[0].classList.add('display-none');
        }

        if(id !== this.section.getJsElements()[1].id) {

            this.section.getJsElements()[1].classList.add('display-none');
        }
    }

    /*
     * After clicking on 'fullscreen button' to run the fullscreen method
     *
     * @param object editorElement div tag (editor)
     * @param object editorBodyElement body tag
     * @param object element anchor tag (button) 
    */
    setOnclickEventFullscreen(editorElement, editorBodyElement, element) {

        var button = this;

        element.addEventListener("click", function() { 
                
            button.fullscreen(editorElement, editorBodyElement);
        }); 
    }

    /*
     * To show editor in fullscreen mode to have a better ux
     *
     * @param object editorElement div tag (editor)
     * @param object editorBodyElement body tag
    */
    fullscreen(editorElement, editorBodyElement) {

        editorElement.classList.add("fullscreen");
        editorBodyElement.append(editor.getElement());
    }

    /*
     * After clicking on 'zoom in button' to increase the font size in the editor to have a better ux
     *
     * @param object element anchor tag (button)
     * @param object editor Editor
    */
    setOnclickEventZoomIn(element, editor) {

        element.addEventListener("click", function() { 

            editor.size++;
            editor.getElement().style.fontSize = editor.size + "px";
        }); 
    }

    /*
     * After clicking on 'zoom out button' to decrease the font size in the editor to have a better ux
     *
     * @param object element anchor tag (button)
     * @param object editor Editor
    */
    setOnclickEventZoomOut(element, editor) {

        element.addEventListener("click", function() { 

            editor.size--;
            editor.getElement().style.fontSize = editor.size + "px";
        }); 
    }
}