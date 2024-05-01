class FileContainer {

    constructor(sidebar = null) {

        this.sidebar = sidebar;
        this.elements = [];
        this.checkboxes = [];
        
        this.setElements();
    }

    /*
     * To get necessary elements
    */
    getElements() {

        return this.elements;
    }

    getCheckboxElements() {

        return this.checkboxes;
    }

    getFilesContainerElement() {

        return document.querySelector('.filesContainer');
    }

    getCurrentSelectedFileElement() {

        return document.querySelector('.selected');
    }

    getAllElements() {

        var elements = document.querySelector('.filesContainer');
        var collection = [];

        for(var element of elements.children[0].children) {

            if(element !== null && typeof element !== 'undefined') {

                collection.push(element);
            }
        }

        return collection;
    }

    setElements() {

        var elements = document.querySelectorAll('.fileContainer');

        for(var element of elements) {

            if(element.classList.contains('folder') === false) {

                this.elements.push(element);
            }
        }
    }

    setCheckboxElements() {

        for(var element of this.elements) {

            this.checkboxes.push(element.children[1]);
        }
    }

    /*
     * After clicking on 'a file in the overview of all files' to run the clearSelection, showFileInfo, setCurrentSelectedFileElementOnclick methods
    */
    setElementOnclicks() {

        var sidebar = this.sidebar;
        var fileContainer = this;
        var elements = this.elements;
        var checkboxElements = this.getCheckboxElements();

        for(var element of elements) {

            if(element !== null && typeof element !== 'undefined') {
               
                element.children[0].onclick = function() {

                    fileContainer.clearSelection(elements, checkboxElements, sidebar);
                    fileContainer.showFileInfo(this, checkboxElements, sidebar);
            
                    sidebar.setCurrentSelectedFileElementOnclick(sidebar.getCurrentSelectedFileElement());
                };
            }
        }
    }

    /*
     * After clicking on 'a checkbox of a file in the overview of all files' to run the deleteSelection, toggleDeleteForm methods
    */
    setCheckboxElementOnclicks() {
        
        var fileContainer = this;
        var deleteInputElement = this.sidebar.getDeleteInputElement();
        var sidebar = this.sidebar;
        var checkboxElements = this.getCheckboxElements();
        var elements = this.elements;

        for(var element of checkboxElements) {

            if(element !== null && typeof element !== 'undefined') {

                element.onclick = function() {
                    
                    fileContainer.deleteSelection(this, deleteInputElement, sidebar);
                    fileContainer.toggleDeleteForm(checkboxElements, elements, sidebar);
                };
            }
        }
    }

    /*
     * To get the 'file container element' (after closing the bigger overview of file)
    */
    getReadFileContainerElement() {

        var elements = document.querySelectorAll('.fileContainer')

        for(var element of elements) {

            if(element.children.length === 1 || element.children[0].classList.contains('deleteSelection') && element.children.length === 2 && element.children[1].classList.contains('selected') === true) {

                return element;
            }
        }
    }

    /*
     * To show a pdf file width a bigger width (after clicking on the read button or clicking on a file after selected) to have a better view of the file
     *
     * @param object imgElement img tag (pdf data)
    */
    createIframeElement(imgElement) {

        var element = document.createElement("iframe");
        element.setAttribute("src", "/" + imgElement.dataset.folder + "/" + imgElement.dataset.filename);
        element.classList.add('read-iframe')

        return element;
    }

    /*
     * To add the ids to the delete input field to submit to remove the selection of files
     *
     * @param object element input tag (file checkbox)
     * @param object input input tag (delete button)
     * @param object sidebar Sidebar 
    */
    deleteSelection(element, input, sidebar) {

        sidebar.getDeleteFormElement().classList.remove('display-none')
        sidebar.getMainButtonContainerElement().children[0].classList.add('display-none-important')
        sidebar.getUpdateFileFormElement().classList.add('display-none')
        sidebar.getSearchElement().classList.add('display-none')
        sidebar.getTotalElement().classList.add('display-none')
        sidebar.getFilterElement().classList.add('display-none')
        sidebar.getButtonContainerElement().classList.add('display-none-important')
    
        this.toggleSelectedDeleteClass(element);
        
        if(element.previousElementSibling.classList.contains('selected-delete') === true || element.previousElementSibling.previousElementSibling !== null && element.previousElementSibling.previousElementSibling.classList.contains('selected-delete') === true) {
    
            input.value += element.previousElementSibling.dataset.id + ",";
        } else {
            input.value = input.value.replace(element.previousElementSibling.dataset.id + ",", "");
        }
    }

    /*
     * To show the file is selected 
     *
     * @param object element input tag (file checkbox)
    */
    toggleSelectedDeleteClass(element) {

        if(element.previousElementSibling.previousElementSibling !== null) {
            element.previousElementSibling.previousElementSibling.classList.toggle('selected-delete')
        } else {
            element.previousElementSibling.classList.toggle('selected-delete')
        }
    }

    /*
     * To show contents of file in sidebar and file is selected
     *
     * @param object element img or video tag (file)
     * @param array checkboxElements input tags (checkboxes) 
     * @param object sidebar Sidebar
    */
    showFileInfo(element, checkboxElements, sidebar) {

        element.classList.add('selected');
    
        if(element.classList.contains('deselect') === false) {
            
            sidebar.getInfoContainerElement().classList.remove('display-none')
            sidebar.getUpdateFileFormElement().classList.add('display-none')
            sidebar.getSearchElement().classList.add('display-none')
            sidebar.getTotalElement().classList.add('display-none')
            sidebar.getFilterElement().classList.add('display-none')
            sidebar.getButtonContainerElement().classList.add('display-none-important')
            sidebar.getMainButtonContainerElement().children[0].classList.add('display-none-important')
    
            if(this.ifAnyElementHasSelectedDelete(checkboxElements) === false) {
                
                sidebar.getMainButtonContainerElement().children[2].classList.remove('display-none-important')
            }
        }
    
        var nodetype = this.getFileNodeType(element.dataset.filetype);
        var fileNode = this.createNode(nodetype, element.dataset.folder, element.dataset.filename);
    
        this.clearCurrentFile(sidebar.getCurrentFileElement());
        sidebar.getCurrentFileElement().append(fileNode);
    
        this.setEqualFileContainerHeight(sidebar.getCurrentFileElement());
        window.addEventListener("resize", function() {
    
            this.setEqualFileContainerHeight(sidebar.getCurrentFileElement());
        }); 
    
        sidebar.getCurrentFiletypeElement().innerText = element.dataset.filetype;
        sidebar.getCurrentFilesizeElement().innerText = parseFloat(element.dataset.filesize / 1000000).toFixed(2) + 'MB';
        sidebar.getCurrentDescriptionElement().value = element.dataset.description;
        sidebar.getCurrentFileFolderElement().innerText = "/" + element.dataset.folder + '/' + element.dataset.filename;
        sidebar.getCurrentFilenameElement().value = element.dataset.filename;
        sidebar.getCurrentFolderElement().setAttribute('data-folder', element.dataset.folder);
    
        if(sidebar.getUpdateFilenameButtonElement() !== null && typeof sidebar.getUpdateFilenameButtonElement() !== 'undefined') {
    
            sidebar.getUpdateFilenameButtonElement().setAttribute('value', element.dataset.id);
        }
        if(sidebar.getUpdateDescriptionButtonElement() !== null && typeof sidebar.getUpdateDescriptionButtonElement() !== 'undefined') {
            
            sidebar.getUpdateDescriptionButtonElement().setAttribute('value', element.dataset.id);
        }
    }

    /*
     * To get the type of file
     *
     * @param string elementType type of file
    */
    getFileNodeType(elementType) {

        var nodetype = '';

        switch (elementType) {

            case 'image/png':
    
                nodetype = 'img';
            break;
            case 'image/webp':
    
                nodetype ='img';
            break;
            case 'image/gif':
    
                nodetype = 'img';
            break;
            case 'image/jpeg':
    
                nodetype = 'img';
            break;
            case 'image/svg+xml':
    
                nodetype = 'img';
            break;
            case 'video/mp4':
    
                nodetype = 'video';
            break;
            case 'video/quicktime':
    
                nodetype = 'video';
            break;
            case 'application/pdf':
    
                nodetype = 'iframe';
            break;
            default:
                nodetype = 'img';
        }

        return nodetype;
    }
    
    /*
     * To show the file in the sidebar
     *
     * @param string type type of file
     * @param string folder folder name
     * @param string filename filename
    */
    createNode(type, folder, filename) {

        var node = document.createElement(type);
        node.setAttribute('src', "/" + folder + '/' + filename);
    
        if(type === 'video') {
    
            node.setAttribute('controls', true);
        }
    
        return node;
    }

    /*
     * To remove the file after new file is selected from sidebar
     *
     * @param object file div tag (sidebar file container)
    */
    clearCurrentFile(file) {

        if(file.children.length !== 0) {
    
            file.children[0].remove();
        }
    }

    /*
     * To set the same height as width for the 'sidebar file container' element to show the file like a square
     *
     * @param object file div tag (sidebar file container)
    */
    setEqualFileContainerHeight(element = null) {

        element.style.height = element.clientWidth + 'px';
    }

    /*
     * To select files individually (to 'clear' last selected file selection and to deselect the file)
     *
     * @param array elements file container elements
     * @param array checkboxElements input checkbox elements
     * @param object sidebar Sidebar
    */
    clearSelection(elements, checkboxElements, sidebar) {

        for(var element of elements) {
    
            if(element.children[0].classList.contains('deselect') ) {
    
                element.children[0].classList.remove('deselect')
                element.children[0].classList.remove('selected')
    
            } else if(element.children[0].classList.contains('selected') === true) {
    
                element.children[0].classList.add('deselect')
                element.children[0].classList.remove('selected')
                sidebar.getInfoContainerElement().classList.add('display-none')
                sidebar.getMainButtonContainerElement().children[2].classList.add('display-none-important')
    
                if(this.ifAnyElementHasSelectedDelete(checkboxElements) === false) {
    
                    sidebar.getUpdateFileFormElement().classList.remove('display-none')
                    sidebar.getSearchElement().classList.remove('display-none')
                    sidebar.getTotalElement().classList.remove('display-none')
                    sidebar.getFilterElement().classList.remove('display-none')
                    sidebar.getButtonContainerElement().classList.remove('display-none-important')
                    sidebar.getMainButtonContainerElement().children[0].classList.remove('display-none-important')
                }
            }
        }
    }

    /*
     * To show/hide the delete button (and to show sidebar elements after deselection)
     *
     * @param array checkboxElements input checkbox elements
     * @param array elements file container elements
     * @param object sidebar Sidebar
    */
    toggleDeleteForm(checkboxElements, elements, sidebar) {

        sidebar.getMainButtonContainerElement().children[2].classList.add('display-none-important')
        
        if(this.ifAnyElementHasSelectedDelete(checkboxElements) === false) {
    
            sidebar.getMainButtonContainerElement().children[1].classList.add('display-none')
            
            if(this.ifAnyElementHasSelected(elements) === false) {
    
                sidebar.getMainButtonContainerElement().children[0].classList.remove('display-none-important')
                sidebar.getUpdateFileFormElement().classList.remove('display-none')
                sidebar.getSearchElement().classList.remove('display-none')
                sidebar.getTotalElement().classList.remove('display-none')
                sidebar.getFilterElement().classList.remove('display-none')
                sidebar.getButtonContainerElement().classList.remove('display-none-important')
                
            } else {
    
                sidebar.getMainButtonContainerElement().children[2].classList.remove('display-none-important')
            }
        } 
    }

    /*
     * To check if file is selected to remove
     *
     * @param array elements input checkbox elements
    */
    ifAnyElementHasSelectedDelete(elements) {

        var hasClass = false;
    
        for(var element of elements) {
    
            if(element.previousElementSibling.classList.contains('selected-delete') === true || element.previousElementSibling.previousElementSibling !== null && element.previousElementSibling.previousElementSibling.classList.contains('selected-delete')) {
    
                hasClass = true;
            } 
        }
    
        return hasClass;
    }

    /*
     * To check if file is selected (to show file)
     *
     * @param array elements input checkbox elements
    */
    ifAnyElementHasSelected(elements) {

        var hasClass = false;
    
        for(var element of elements) {
    
            if(element.children[0].classList.contains('selected') === true && element.children[0].classList.contains('deselect') === false) {
    
                hasClass = true;
            } 
        }
    
        return hasClass
    }
}






















