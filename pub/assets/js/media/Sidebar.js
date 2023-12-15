class Sidebar {

    constructor() {

        this.setOnclickCloseButton();
        this.setOnclickReadButton();
    }

    /*
     * To get necessary elements
    */
    getUpdateFileFormElement() {

        return document.querySelector('.uploadFileForm');
    }

    getButtonContainerElement() {

        return document.querySelector('.buttonContainer');
    }

    getMainButtonContainerElement() {

        return document.querySelector('.mainButtonContainer');
    }

    getDeleteFormElement() {

        return document.querySelector('.deleteForm');
    }

    getDeleteInputElement() {

        return document.getElementById('selectedFiles');
    }

    getInfoContainerElement() {

        return document.querySelector('.fileInfoContainer');
    }

    getCurrentFileElement() {

        return document.getElementById('currentFile');
    }

    getCurrentFileFolderElement() {

        return document.getElementById('currentFolderFilename');
    }

    getCurrentFilesizeElement() {

        return document.getElementById('currentFilesize');
    }

    getCurrentFiletypeElement() {

        return document.getElementById('currentFiletype');
    }

    getCurrentFilenameElement() {

        return document.getElementById('currentFilename');
    }

    getCurrentFolderElement() {

        return document.getElementById('currentFolder');
    }

    getCurrentDescriptionElement() {

        return document.getElementById('currentDescription');
    }

    getUpdateFilenameButtonElement() {

        return document.getElementById('update');
    }

    getUpdateDescriptionButtonElement() {

        return document.getElementById('updateDescription');
    }

    getCurrentSelectedFileElement() {

        return document.getElementById('currentFile');
    }

    getSearchElement() {

        return document.querySelector('.searchFormCreate');
    }

    getTotalElement() {

        return document.querySelector('.totalContainer');
    }

    getFilterElement() {

        return document.querySelector('.filterForm');
    }

    getReadButtonElement() {

        return document.querySelector('.read');
    }

    getCloseButtonElement() {

        return document.querySelector('.close');
    }

    /*
     * After file is selected and clicking on the file itself (only type of image files) to run the displayReadImageContainer method
     *
     * @param element object div tag (clicked file)
    */
    setCurrentSelectedFileElementOnclick(element) {

        var sidebar = this;
        var closeButton = this.getCloseButtonElement();
        var readButton = this.getReadButtonElement();

        element.onclick = function() {
     
            sidebar.displayReadImageContainer(readButton, closeButton);
        };
    }

    /*
     * After clicking on the 'read button' to run the displayReadImageContainer method
    */
    setOnclickReadButton() {

        var sidebar = this;
        var closeButton = this.getCloseButtonElement();

        this.getReadButtonElement().onclick = function() {
                    
            sidebar.displayReadImageContainer(this, closeButton);
        };
    }

    /*
     * After clicking on the 'close button' to run the closeReadImageContainer method
    */
    setOnclickCloseButton() {

        var sidebar = this;
        var readButton = this.getReadButtonElement();

        this.getCloseButtonElement().onclick = function() {

            sidebar.closeReadImageContainer(this, readButton);
        }
    }

    /*
    * To close the 'bigger view of file' to show the overview of files
    *
    * @param object element anchor tag (close button)
    * @param object readButtonElement anchor tag (read button)
    */
    closeReadImageContainer(closeButtonElement, readButtonElement) {

        closeButtonElement.classList.add('display-none-important');
        readButtonElement.classList.remove('display-none-important');

        var readImageContainer = new ReadImageContainer();
        readImageContainer.getElement().classList.add('display-none');
        
        var fileContainer = new FileContainer();
        fileContainer.getFilesContainerElement().classList.remove('display-none');

        if(readImageContainer.getFileElement().classList.contains('read-iframe') === true) {

            readImageContainer.getFileElement().classList.remove('read-iframe');
            readImageContainer.getFileElement().remove();
        } else if(readImageContainer.getFileElement().classList.contains('read-image') === true) {

            readImageContainer.getFileElement().classList.remove('read-image');
            fileContainer.getReadFileContainerElement().append(readImageContainer.getFileElement());
            fileContainer.getReadFileContainerElement().append(fileContainer.getReadFileContainerElement().children[0])
        } else if(readImageContainer.getFileElement().classList.contains('read-video') === true) {

            readImageContainer.getFileElement().classList.remove('read-video');
            readImageContainer.getFileElement().removeAttribute('controls');

            fileContainer.getReadFileContainerElement().append(readImageContainer.getFileElement());
            fileContainer.getReadFileContainerElement().append(fileContainer.getReadFileContainerElement().children[0])
        }

        var ranger = new Ranger();
        ranger.getElement().classList.remove('display-none');
    }

    /*
    * To show the 'selected file' with a bigger width to have a better view of the file
    *
    * @param object readButtonElement anchor tag (read button)
    * @param object closeButtonElement anchor tag (close button)
    */
    displayReadImageContainer(readButtonElement, closeButtonElement) {

        readButtonElement.classList.add('display-none-important');
        closeButtonElement.classList.remove('display-none-important');

        var readImageContainer = new ReadImageContainer();
        readImageContainer.getElement().classList.remove('display-none');

        var ranger = new Ranger();
        ranger.getElement().classList.add('display-none');

        var fileContainer = new FileContainer();
        fileContainer.getFilesContainerElement().classList.add('display-none');

        if(fileContainer.getCurrentSelectedFileElement().classList.contains('imgFile') === true) {
            
            fileContainer.getCurrentSelectedFileElement().classList.add('read-image');
            readImageContainer.getElement().append(fileContainer.getCurrentSelectedFileElement());
        } else if(fileContainer.getCurrentSelectedFileElement().classList.contains('videoFile') === true) {

            fileContainer.getCurrentSelectedFileElement().classList.add('read-video');
            fileContainer.getCurrentSelectedFileElement().setAttribute('controls', "true");
            readImageContainer.getElement().append(fileContainer.getCurrentSelectedFileElement());

        } else if(fileContainer.getCurrentSelectedFileElement().classList.contains('pdfFile') === true) {

            readImageContainer.getElement().append(fileContainer.createIframeElement(fileContainer.getCurrentSelectedFileElement()));
        }
    }
}