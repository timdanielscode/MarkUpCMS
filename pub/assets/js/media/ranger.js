var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {

    var ranger = document.getElementById('ranger');

    ranger.addEventListener("change", function(){ 
        
        for(var fileContainer of fileContainers) {

            if(fileContainer.classList.contains('folder') ) {

                fileContainer.children[1].style.fontSize = this.value / 8 + "px";
            }

            if(fileContainer.children[0].classList.contains('iframeLayer')) {
                element = fileContainer.children[1];
            } else {
                element = fileContainer.children[0];
            }

            element.style.width = this.value + "px";
            element.style.height = this.value + "px";
        }
    }); 
}