class Progress {

    constructor() {

        this.element;
        this.layers;
        this.stopElements;
        this.setElement();
        this.setLayers();
        this.setStopElements();
    }

    setElement() {

        var element = document.getElementById('progressInfoItem');

        if(element !== null && typeof element !== 'undefined') {

            this.element = element;
        }
    }

    getElement() {

        return this.element;
    }

    setLayers() {

        var elements = document.querySelectorAll(".layer");

        if(elements !== null && typeof elements !== 'undefined') {

            this.layers = elements;
        }
    }

    setStopElements() {

        var elements = document.querySelectorAll('.stopMousemoveEvent');

        if(elements !== null && typeof elements !== 'undefined') {

            this.stopElements = elements;
        }
    }

    setMouseMoveEventShow() {

        for(var element of this.layers) {

            var progressElement = this.element;

            element.addEventListener("mousemove", function(event) {

                showElement(this, progressElement, event);
            });
        }
    }

    setMouseMoveEventHide() {

        for(var element of this.stopElements) {

            var progressElement = this.element;

            element.addEventListener("mousemove", function(event) {

                hideElement(progressElement);
            });
        }
    }
}

function showElement(element, progressElement, event) {

    var cursorX = event.clientX;
    var cursorY = event.clientY;

    var label = element.nextElementSibling;
    var progressBar = element.nextElementSibling.nextElementSibling

    progressElement.innerText = label.innerText + ": " + progressBar.value;

    progressElement.style.left = cursorX + 20 + 'px';
    progressElement.style.top = cursorY + 20 + 'px';
    progressElement.style.display = 'block';
}

function hideElement(element) {

    element.style.display = '';
}