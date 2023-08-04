var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {
        
    var fileIds = [];

    for(var fileContainer of fileContainers) {

        if(fileContainer.classList.contains('folder') === false) {

            fileContainer.addEventListener("click", function(){ 

                var deleteInputField = document.getElementById('selectedFiles');
                this.children[0].classList.toggle('selected')

                if(this.children[0].classList.contains('selected') === true) {

                    fileIds.push(this.children[0].dataset.id);
                } else {
                    fileIds = fileIds.filter(e => e !== this.children[0].dataset.id)
                }

                deleteInputField.value = fileIds;
            }); 
        }
    }
}