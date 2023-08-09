class Sidebar {

    constructor() {

        this.infoContainer;
        this.setInfoContainerElement();
        this.currentFileElement;
        this.setCurrentFileElement();
        this.currentFileFolderElement;
        this.setCurrentFileFolderElement();
        this.currentFilesizeElement;
        this.setCurrentFilesizeElement();
        this.currentFiletypeElement;
        this.setCurrentFiletypeElement();
        this.currentFilenameElement;
        this.setCurrentFilenameElement();
        this.currentFolderElement;
        this.setCurrentFolderElement();
        this.updateButtonElement;
        this.setUpdatebuttonElement();

        this.deleteFormElement;
        this.setDeleteFormElement();

        this.mainButtonContainerElement;
        this.setMainButtonContainerElement();

        this.updateFileFormElement;
        this.setUpdateFileFormElement();

        this.buttonContainerElement;
        this.setButtonContainerElement();
    }

    setUpdateFileFormElement() {

        var element = document.querySelector('.uploadFileForm');

        if(element !== null && element !== 'undefined') {

            this.updateFileFormElement = element;
        }
    }

    setButtonContainerElement() {
        
        var element = document.querySelector('.buttonContainer');

        if(element !== null && element !== 'undefined') {

            this.buttonContainerElement = element;
        }
    }

    setMainButtonContainerElement() {

        var element = document.querySelector('.mainButtonContainer');

        if(element !== null && typeof element !== 'undefined') {

            this.mainButtonContainerElement = element;
        }
    }

    setDeleteFormElement() {

        var element = document.querySelector('.deleteForm');

        if(element !== null && typeof element !== 'undefined') {

            this.deleteFormElement = element;
        }
    }

    getDeleteInputElement() {

        var element = document.getElementById('selectedFiles');
        
        if(element !== null && typeof element !== 'undefined') {

            return element;
        }
    }

    setInfoContainerElement() {

        var element = document.querySelector('.fileInfoContainer');

        if(element !== null && typeof element !== 'undefined') {

            this.infoContainer = element;
        }
    }

    getInfoContainerElement() {

        return this.infoContainer;
    }

    setCurrentFileElement() {

        var element = document.getElementById('currentFile');

        if(element !== null && typeof element !== 'undefined') {

            this.currentFileElement = element;
        }
    }

    setCurrentFileElement() {

        var element = document.getElementById('currentFile');

        if(element !== null && typeof element !== 'undefined') {

            this.currentFileElement = element;
        }
    }

    setCurrentFileFolderElement() {

        var element = document.getElementById('currentFolderFilename');

        if(element !== null && typeof element !== 'undefined') {

            this.currentFileFolderElement = element;
        }
    }
    setCurrentFilesizeElement() {

        var element = document.getElementById('currentFilesize');

        if(element !== null && typeof element !== 'undefined') {

            this.currentFilesizeElement = element;
        }
    }
    setCurrentFiletypeElement() {

        var element = document.getElementById('currentFiletype');

        if(element !== null && typeof element !== 'undefined') {

            this.currentFiletypeElement = element;
        }
    }
    setCurrentFilenameElement() {

        var element = document.getElementById('currentFilename');

        if(element !== null && typeof element !== 'undefined') {

            this.currentFilenameElement = element;
        }
    }

    setCurrentFolderElement() {

        var element = document.getElementById('currentFolder');

        if(element !== null && typeof element !== 'undefined') {

            this.currentFolderElement = element;
        }
    }

    setUpdatebuttonElement() {

        var element = document.getElementById('update');

        if(element !== null && typeof element !== 'undefined') {

            this.updateButtonElement = element;
        }
    }
}