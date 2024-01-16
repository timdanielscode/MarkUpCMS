var fileContainer = new FileContainer(new Sidebar());

if(fileContainer.elements.length !== 0 ) {

    var ranger = new Ranger();
    ranger.setOnChangeEvent(ranger.element, fileContainer.getAllElements());

    fileContainer.setElementOnclicks();
    fileContainer.setCheckboxElements();

    if(fileContainer.getCheckboxElements().length !== 0) {

        fileContainer.setCheckboxElementOnclicks();
    }
} 