class Ranger {

    constructor() {

        this.element;
        this.setElement();
    }

    setElement() {

        var element = document.getElementById('ranger');

        if(element !== null && typeof element !== 'undefined') {

            this.element = element;
        }
    }

    getElement() {

        return this.element;
    }
}