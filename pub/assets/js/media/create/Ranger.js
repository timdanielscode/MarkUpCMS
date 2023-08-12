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

    setOnChangeEvent(element, fileContainerElements) {

        element.onchange = function() {
                    
            resizeElements(element, fileContainerElements);
        };
    }
}

function resizeElements(ranger, elements) {

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