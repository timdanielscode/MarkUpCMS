class Ranger {

    constructor() {

        this.element;
        this.setElement();
    }

    /*
     * To get necessary elements
    */
    setElement() {

        var element = document.getElementById('ranger');

        if(element !== null && typeof element !== 'undefined') {

            this.element = element;
        }
    }

    getElement() {

        return this.element;
    }

    /*
     * After sliding 'the slider element' to run the resizeElements method
     *
     * @param object element input tag (ranger element)
     * @param object fileContainerElements a | div tags (folder elements and file container elements)
    */
    setOnChangeEvent(element, fileContainerElements) {

        var ranger = this; 

        element.onchange = function() {
                    
            ranger.resizeElements(this, fileContainerElements);
        };
    }

    /*
     * To adjust the width of the files and folders to have a bigger overview or to have a better view of files
     *
     * @param object ranger input tag (ranger element)
     * @param object elements a | div tags (folder elements and file container elements)
    */
    resizeElements(ranger, elements) {

        for(var element of elements) {
    
            if(element.classList.contains('fileContainer') === true && element.children[0].classList.contains('iframeLayer') === false) {
    
                element.children[0].style.width = ranger.value + "px";
                element.children[0].style.height = ranger.value + "px";
            } else if(element.children[0].classList.contains('fileContainer') === true && element.children[0].classList.contains('iframeLayer') === false) {
    
                element.children[0].children[0].style.width = ranger.value + "px";
                element.children[0].children[0].style.height = ranger.value + "px";
                element.children[0].children[1].style.fontSize = ranger.value / 8 + "px";
                element.children[0].children[1].style.fontSize = ranger.value / 8 + "px";
    
            } else if(element.children[0].classList.contains('iframeLayer') === true) {
    
                element.children[1].style.width = ranger.value + "px";
                element.children[1].style.height = ranger.value + "px";
            }
        }
    }
}

