var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {
        
    var fileIds = [];

    for(var fileContainer of fileContainers) {

        if(fileContainer.classList.contains('folder') === false) {

            if(fileContainer.children[0].classList.contains('iframeLayer')) {

                element = fileContainer.children[2];
            } else {
                element = fileContainer.children[1];
            }

            element.addEventListener("click", function(){ 

                var deleteInputField = document.getElementById('selectedFiles');
                this.previousElementSibling.classList.toggle('selected-delete')

                if(this.previousElementSibling.classList.contains('selected-delete') === true) {

                    fileIds.push(this.previousElementSibling.dataset.id);
                    
                } else {
                    fileIds = fileIds.filter(e => e !== this.previousElementSibling.dataset.id)
                }

                deleteInputField.value = fileIds;
            }); 
        }
    }
}