var fileContainer = new FileContainer(new Sidebar());

if(fileContainer.elements.length !== 0 ) {

    fileContainer.setElementOnclicks();
    fileContainer.setCheckboxElements();

    if(fileContainer.getCheckboxElements().length !== 0) {

        fileContainer.setCheckboxElementOnclicks();
    }
} 