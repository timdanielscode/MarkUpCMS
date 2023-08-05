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

        for(var element of elements) {

            if(element !== null && typeof element !== 'undefined') {
               
                element.children[0].onclick = function() {
                    
                    clearSelection(elements);
                    showFileInfo(this, sidebar);
                };
            }
        }
    }

    setCheckboxElementOnclicks() {
        
        var deleteInputElement = sidebar.getDeleteInputElement();

        for(var element of this.getCheckboxElements()) {

            if(element !== null && typeof element !== 'undefined') {

                element.onclick = function() {
                    
                    deleteSelection(this, deleteInputElement);
                };
            }
        }
    }
}

function deleteSelection(element, input) {

    element.previousElementSibling.classList.toggle('selected-delete')

    if(element.previousElementSibling.classList.contains('selected-delete') === true) {

        input.value += element.previousElementSibling.dataset.id + ",";
    } else {
        input.value = input.value.replace(element.previousElementSibling.dataset.id + ",", "");
    }
}

function showFileInfo(element, sidebar) {

    element.classList.add('selected');

    sidebar.infoContainer.classList.remove('display-none');
    
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

function clearSelection(elements) {

    for(var element of elements) {

        if(element.children[0].classList.contains('selected') === true) {
    
            element.children[0].classList.remove('selected');
        }
    }
}