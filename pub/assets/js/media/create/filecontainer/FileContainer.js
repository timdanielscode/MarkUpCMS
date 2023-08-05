class FileContainer {

    constructor(sidebar) {

        this.sidebar = new Sidebar();
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