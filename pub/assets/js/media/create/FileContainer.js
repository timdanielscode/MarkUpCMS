class FileContainer {

    constructor(sidebar) {

        this.sidebar = sidebar;
        this.elements = [];
        this.checkboxes = [];
        this.setElements();
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

            if(element.children[0].classList.contains('iframeLayer')) {

                this.checkboxes.push(element.children[2]);
            } else {
                this.checkboxes.push(element.children[1]);
            }
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
}

function deleteSelection(element, input, sidebar) {

    sidebar.deleteFormElement.classList.remove('display-none')
    sidebar.mainButtonContainerElement.children[0].classList.add('display-none-important')
    sidebar.updateFileFormElement.classList.add('display-none')
    sidebar.buttonContainerElement.classList.add('display-none-important')
    element.previousElementSibling.classList.toggle('selected-delete')

    if(element.previousElementSibling.classList.contains('selected-delete') === true) {

        input.value += element.previousElementSibling.dataset.id + ",";
    } else {
        input.value = input.value.replace(element.previousElementSibling.dataset.id + ",", "");
    }
}

function showFileInfo(element, checkboxElements, sidebar) {

    element.classList.add('selected');

    if(element.classList.contains('deselect') === false) {
        
        sidebar.infoContainer.classList.remove('display-none')
        sidebar.updateFileFormElement.classList.add('display-none')
        sidebar.buttonContainerElement.classList.add('display-none-important')
        sidebar.mainButtonContainerElement.children[0].classList.add('display-none-important')

        if(ifAnyElementHasSelectedDelete(checkboxElements) === false) {
            
            sidebar.mainButtonContainerElement.children[2].classList.remove('display-none-important')
        }
    }

    var file = getCorrectElement(element);

    var nodetype = getFileNodeType(file.dataset.filetype);
    var fileNode = createNode(nodetype, file.dataset.folder, file.dataset.filename);

    clearCurrentFile(sidebar.currentFileElement);
    sidebar.currentFileElement.append(fileNode);

    setEqualFileContainerHeight(sidebar.currentFileElement);
    window.addEventListener("resize", function() {

        setEqualFileContainerHeight(sidebar.currentFileElement);
    }); 

    sidebar.currentFiletypeElement.innerText = file.dataset.filetype;
    sidebar.currentFilesizeElement.innerText = parseFloat(file.dataset.filesize / 1000000).toFixed(2) + 'MB';
    sidebar.currentDescriptionElement.innerText = file.dataset.description;

    sidebar.currentFileFolderElement.innerText = "/" + file.dataset.folder + '/' + file.dataset.filename;
    sidebar.currentFilenameElement.value = file.dataset.filename;
    sidebar.currentFolderElement.setAttribute('data-folder', file.dataset.folder);
    sidebar.updateButtonElement.setAttribute('value', file.dataset.id);
}

function getCorrectElement(element) {

    if(element.classList.contains('iframeLayer')) {
                
        file = element.nextElementSibling;
    } else {
        file = element;
    }

    return file;
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
            sidebar.buttonContainerElement.classList.remove('display-none-important')
            
        } else {

            sidebar.mainButtonContainerElement.children[2].classList.remove('display-none-important')
        }
    } 
}

function ifAnyElementHasSelectedDelete(elements) {

    var hasClass = false;

    for(var element of elements) {

        if(element.previousElementSibling.classList.contains('selected-delete') === true) {

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