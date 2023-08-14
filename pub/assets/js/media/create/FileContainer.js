class FileContainer {

    constructor(sidebar = null) {

        this.sidebar = sidebar;
        this.elements = [];
        this.checkboxes = [];
        this.setElements();
        this.fileContainerElement;
        this.setFilesContainerElement();
        this.currentSelectedFileElement;
        this.setCurrentSelectedFileElement();
    }

    setFilesContainerElement() {

        var element = document.querySelector('.filesContainer');

        if(element !== null && typeof element !== 'undefined') {

            this.filesContainerElement = element;
        }
    }

    setCurrentSelectedFileElement() {

        var element = document.querySelector('.selected');

        if(element !== null && typeof element !== 'undefined') {

            this.currentSelectedFileElement = element;
        }
    }

    setElements() {

        var elements = document.querySelectorAll('.fileContainer');

        for(var element of elements) {

            if(element.classList.contains('folder') === false) {

                this.elements.push(element);
            }
        }
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

    setCheckboxElements() {

        for(var element of this.elements) {

            this.checkboxes.push(element.children[1]);
        }
    }

    getElements() {

        return this.elements;
    }

    getCheckboxElements() {

        return this.checkboxes;
    }

    setElementOnclicks() {

        var sidebar = this.sidebar;
        var elements = this.elements;
        var checkboxElements = this.getCheckboxElements();

        for(var element of elements) {

            if(element !== null && typeof element !== 'undefined') {
               
                element.children[0].onclick = function() {
                    
                    clearSelection(elements, checkboxElements, sidebar);
                    showFileInfo(this, checkboxElements, sidebar);
            
                    sidebar.setCurrentSelectedFileElementOnclick(sidebar.getCurrentSelectedFileElement());
                };
            }
        }
    }

    setCheckboxElementOnclicks() {
        
        var deleteInputElement = this.sidebar.getDeleteInputElement();
        var sidebar = this.sidebar;
        var checkboxElements = this.getCheckboxElements();
        var elements = this.elements;

        for(var element of checkboxElements) {

            if(element !== null && typeof element !== 'undefined') {

                element.onclick = function() {
                    
                    deleteSelection(this, deleteInputElement, sidebar);
                    toggleDeleteForm(checkboxElements, elements, sidebar);
                };
            }
        }
    }

    getReadFileContainerElement() {

        var elements = document.querySelectorAll('.fileContainer')

        for(var element of elements) {

            if(element.children.length === 1 || element.children[0].classList.contains('deleteSelection') && element.children.length === 2 && element.children[1].classList.contains('selected') === true || element.children.length === 2 && element.children[0].classList.contains('pdfFile')) {

                return element;
            }
        }
    }

    createIframeElement(imgElement) {

        var element = document.createElement("iframe");
        element.setAttribute("src", "/" + imgElement.dataset.folder + "/" + imgElement.dataset.filename);
        element.classList.add('read-iframe')

        return element;
    }
}

function deleteSelection(element, input, sidebar) {

    sidebar.deleteFormElement.classList.remove('display-none')
    sidebar.mainButtonContainerElement.children[0].classList.add('display-none-important')
    sidebar.updateFileFormElement.classList.add('display-none')
    sidebar.searchElement.classList.add('display-none')
    sidebar.totalElement.classList.add('display-none')
    sidebar.filterElement.classList.add('display-none')
    sidebar.buttonContainerElement.classList.add('display-none-important')

    toggleSelectedDeleteClass(element);
    
    if(element.previousElementSibling.classList.contains('selected-delete') === true || element.previousElementSibling.previousElementSibling !== null && element.previousElementSibling.previousElementSibling.classList.contains('selected-delete') === true) {

        input.value += element.previousElementSibling.dataset.id + ",";
    } else {
        input.value = input.value.replace(element.previousElementSibling.dataset.id + ",", "");
    }
}

function toggleSelectedDeleteClass(element) {

    if(element.previousElementSibling.previousElementSibling !== null) {
        element.previousElementSibling.previousElementSibling.classList.toggle('selected-delete')
    } else {
        element.previousElementSibling.classList.toggle('selected-delete')
    }
}

function showFileInfo(element, checkboxElements, sidebar) {

    element.classList.add('selected');

    if(element.classList.contains('deselect') === false) {
        
        sidebar.infoContainer.classList.remove('display-none')
        sidebar.updateFileFormElement.classList.add('display-none')
        sidebar.searchElement.classList.add('display-none')
        sidebar.totalElement.classList.add('display-none')
        sidebar.filterElement.classList.add('display-none')
        sidebar.buttonContainerElement.classList.add('display-none-important')
        sidebar.mainButtonContainerElement.children[0].classList.add('display-none-important')

        if(ifAnyElementHasSelectedDelete(checkboxElements) === false) {
            
            sidebar.mainButtonContainerElement.children[2].classList.remove('display-none-important')
        }
    }

    var nodetype = getFileNodeType(element.dataset.filetype);
    var fileNode = createNode(nodetype, element.dataset.folder, element.dataset.filename);

    clearCurrentFile(sidebar.currentFileElement);
    sidebar.currentFileElement.append(fileNode);

    setEqualFileContainerHeight(sidebar.currentFileElement);
    window.addEventListener("resize", function() {

        setEqualFileContainerHeight(sidebar.currentFileElement);
    }); 

    sidebar.currentFiletypeElement.innerText = element.dataset.filetype;
    sidebar.currentFilesizeElement.innerText = parseFloat(element.dataset.filesize / 1000000).toFixed(2) + 'MB';
    sidebar.currentDescriptionElement.value = element.dataset.description;
    sidebar.currentFileFolderElement.innerText = "/" + element.dataset.folder + '/' + element.dataset.filename;
    sidebar.currentFilenameElement.value = element.dataset.filename;
    sidebar.currentFolderElement.setAttribute('data-folder', element.dataset.folder);
    sidebar.updateFilenameButtonElement.setAttribute('value', element.dataset.id);
    sidebar.updateDescriptionButtonElement.setAttribute('value', element.dataset.id);
}

function getFileNodeType(elementType) {

    if(elementType !== null && typeof elementType !== 'undefined') {

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
    }

    return nodetype;
}

function createNode(type, folder, filename) {

    if(type !== null && typeof type !== 'undefined') {

        var node = document.createElement(nodetype);
        node.setAttribute('src', "/" + folder + '/' + filename);

        if(type === 'video') {

            node.setAttribute('controls', true);
        }

        return node;
    }
}

function clearCurrentFile(file) {

    if(file.children.length !== 0) {

        file.children[0].remove();
    }
}

function setEqualFileContainerHeight(element = null) {

    element.style.height = element.clientWidth + 'px';
}

function clearSelection(elements, checkboxElements, sidebar) {

    for(var element of elements) {

        if(element.children[0].classList.contains('deselect') ) {

            element.children[0].classList.remove('deselect')
            element.children[0].classList.remove('selected')

        } else if(element.children[0].classList.contains('selected') === true) {

            element.children[0].classList.add('deselect')
            element.children[0].classList.remove('selected')
            sidebar.infoContainer.classList.add('display-none')
            sidebar.mainButtonContainerElement.children[2].classList.add('display-none-important')

            if(ifAnyElementHasSelectedDelete(checkboxElements) === false) {

                sidebar.updateFileFormElement.classList.remove('display-none')
                sidebar.searchElement.classList.remove('display-none')
                sidebar.totalElement.classList.remove('display-none')
                sidebar.filterElement.classList.remove('display-none')
                sidebar.buttonContainerElement.classList.remove('display-none-important')
                sidebar.mainButtonContainerElement.children[0].classList.remove('display-none-important')
            }
        }
    }
}

function toggleDeleteForm(checkboxElements, elements, sidebar) {

    sidebar.mainButtonContainerElement.children[2].classList.add('display-none-important')
    
    if(ifAnyElementHasSelectedDelete(checkboxElements) === false) {

        sidebar.mainButtonContainerElement.children[1].classList.add('display-none')
        
        if(ifAnyElementHasSelected(elements) === false) {

            sidebar.mainButtonContainerElement.children[0].classList.remove('display-none-important')
            sidebar.updateFileFormElement.classList.remove('display-none')
            sidebar.searchElement.classList.remove('display-none')
            sidebar.totalElement.classList.remove('display-none')
            sidebar.filterElement.classList.remove('display-none')
            sidebar.buttonContainerElement.classList.remove('display-none-important')
            
        } else {

            sidebar.mainButtonContainerElement.children[2].classList.remove('display-none-important')
        }
    } 
}

function ifAnyElementHasSelectedDelete(elements) {

    var hasClass = false;

    for(var element of elements) {

        if(element.previousElementSibling.classList.contains('selected-delete') === true || element.previousElementSibling.previousElementSibling !== null && element.previousElementSibling.previousElementSibling.classList.contains('selected-delete')) {

            hasClass = true;
        } 
    }

    return hasClass;
}

function ifAnyElementHasSelected(elements) {

    var hasClass = false;

    for(var element of elements) {

        if(element.children[0].classList.contains('selected') === true && element.children[0].classList.contains('deselect') === false) {

            hasClass = true;
        } 
    }

    return hasClass;

}