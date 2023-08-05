class Sidebar {

    constructor() {

        this.deleteInputElement;
        this.setDeleteElement();
    }

    setDeleteElement() {

        var element = document.getElementById('selectedFiles');
        
        if(element !== null && typeof element !== 'undefined') {

            this.deleteInputElement = element;
        }
    }

    getDeleteInputElement() {

        return this.deleteInputElement;
    }
}