class Progress {

    /*
     * To get necessary elements
    */
    getElement() {

        return document.getElementById('progressInfoItem');
    }

    getLayers() {

        return document.querySelectorAll(".layer");
    }

    getStopElements() {

        return document.querySelectorAll('.stopMousemoveEvent');
    }

    /*
     * After hovering over 'progressbar elements' to run the showElement method
    */
    setMouseMoveEventShow() {

        var progress = this;

        for(var element of this.getLayers()) {

            element.addEventListener("mousemove", function(event) {

                progress.showElement(this, progress.getElement(), event);
            });
        }
    }

    /*
     * After hovering over 'stop mouse event elements' to run the hideElement method
    */
    setMouseMoveEventHide() {

        var progress = this;

        for(var element of this.getStopElements()) {

            element.addEventListener("mousemove", function(event) {

                progress.hideElement(progress.getElement());
            });
        }
    }

    /*
     * To show the progressbar info
     *
     * @param object element div tag (progressbar layer)
     * @param object progressElement div tag (progress info element)
     * @param object event (param element, mousemove)
    */
    showElement(element, progressElement, event) {

        var cursorX = event.clientX;
        var cursorY = event.clientY;
    
        var label = element.nextElementSibling;
        var progressBar = element.nextElementSibling.nextElementSibling
    
        progressElement.innerText = label.innerText + ": " + progressBar.value;
    
        progressElement.style.left = cursorX + 20 + 'px';
        progressElement.style.top = cursorY + 20 + 'px';
        progressElement.style.display = 'block';
    }

    /*
     * To hide the progressbar info
     *
     * @param object element div tag (progress info element)
    */
    hideElement(element) {

        element.style.display = '';
    }
}