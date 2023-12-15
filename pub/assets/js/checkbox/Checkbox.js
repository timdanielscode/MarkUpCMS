class Checkbox {

    constructor() {

        this.values = [];
    }

    /*
     * To get necessary elements
    */
    getElements() {

        return document.querySelectorAll('.deleteCheckbox');
    }

    getDeleteInputElement() {

        return document.getElementById('deleteIds');
    }

    getRecoverInputElement() {

        return document.getElementById('recoverIds');
    }

    /*
     * After clicking on 'checkbox elements' to run the setInputValues method
    */
    setOnclickEvent() {

        var checkbox = this;

        for(var element of this.getElements()) {

            element.addEventListener('click', function() { 

                checkbox.setInputValues(this);
            });
        }
    }

    /*
     * To create a selection to submit to recover or delete 
     *
     * @param element object input tag (checkbox element)
    */
    setInputValues(element) {

        if(this.values.includes(element.value) === true) {
            
            this.values.splice(this.values.indexOf(element.value), 1);
        } else {
            this.values.push(element.value)
        }

        this.getDeleteInputElement().value = this.values;

        if(this.getRecoverInputElement() !== null) {

            this.getRecoverInputElement().value = this.values;
        }
    }
}