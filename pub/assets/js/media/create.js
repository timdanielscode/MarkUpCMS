var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {

    for(var fileContainer of fileContainers) {

        fileContainer.addEventListener("click", function(){ 
            
            if(this.classList.contains('show') === false) {
    
                this.classList.add('show');
                this.previousElementSibling.classList.remove('display-none');
                removeItems();
            } else {
                this.classList.remove('show');
                this.previousElementSibling.classList.add('display-none');
                addItems();
            }
        }); 
    }
    
    function addItems() {
    
        for(var fileContainer of fileContainers) {
    
            if(fileContainer.classList.contains('show') === false) {
        
                fileContainer.classList.remove('display-none')
                
            }
        }
    }
    
    function removeItems() {
    
        for(var fileContainer of fileContainers) {
    
            if(fileContainer.classList.contains('show') === false) {
        
                fileContainer.classList.add('display-none')
            }
        }
    }

    var ranger = document.getElementById('ranger');

    ranger.addEventListener("change", function(){ 
        
        for(var fileContainer of fileContainers) {

            if(fileContainer.classList.contains('folder') ) {

                fileContainer.children[1].style.fontSize = this.value / 5 + "px";
            }

            fileContainer.firstElementChild.style.width = this.value + "px";
            fileContainer.firstElementChild.style.height = this.value + "px";
        }
    }); 

}


