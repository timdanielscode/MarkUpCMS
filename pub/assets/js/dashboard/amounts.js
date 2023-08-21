var progressInfoItem = document.getElementById('progressInfoItem')

var progressContainers = document.querySelectorAll('.progressContainer')
var progressContainerLayers = document.querySelectorAll(".layer");

if(progressContainerLayers !== null) {

    for(var progressContainerLayer of progressContainerLayers) {

        progressContainerLayer.addEventListener("mousemove", function(event) {

            var cursorX = event.clientX;
            var cursorY = event.clientY;

            var label = this.nextElementSibling;
            var progressBar = this.nextElementSibling.nextElementSibling

            progressInfoItem.innerText = label.innerText + ": " + progressBar.value;

            progressInfoItem.style.left = cursorX + 20 + 'px';
            progressInfoItem.style.top = cursorY + 20 + 'px';

            progressInfoItem.classList.add('show-item');
        });
    }
}

var stopMousemoveEventElements = document.querySelectorAll('.stopMousemoveEvent');

for(var stopMousemoveEventElement of stopMousemoveEventElements) {

    stopMousemoveEventElement.addEventListener("mousemove", function(event) {

        progressInfoItem.classList.remove('show-item')
    });
}