var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {

    var ranger = document.getElementById('ranger');

    ranger.addEventListener("change", function(){ 
        
        for(var fileContainer of fileContainers) {

            if(fileContainer.classList.contains('folder') ) {

                fileContainer.children[1].style.fontSize = this.value / 8 + "px";
            }

            fileContainer.firstElementChild.style.width = this.value + "px";
            fileContainer.firstElementChild.style.height = this.value + "px";
        }
    }); 

}


