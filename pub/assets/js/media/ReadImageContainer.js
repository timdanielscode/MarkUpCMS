class ReadImageContainer {

    constructor() {

        this.element;
        this.setElement();
    }

    setElement() {

        var element = document.querySelector('.readImageContainer');

        if(element !== null && typeof element !== 'undefined') {

            this.element = element;
        }
    }

    getElement() {

        return this.element;
    }

    getFileElement() {

        var element = this.element.children;

        return element[0];
    }
}