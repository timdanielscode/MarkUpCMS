var fileContainers = document.querySelectorAll('.fileContainer');

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

