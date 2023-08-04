var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {
        
    var fileIds = [];

    for(var fileContainer of fileContainers) {

        if(fileContainer.classList.contains('folder') === false) {

            fileContainer.children[1].addEventListener("click", function(){ 

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