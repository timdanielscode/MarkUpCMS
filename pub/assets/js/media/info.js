var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {

    for(var fileContainer of fileContainers) {

        fileContainer.children[0].addEventListener("click", function(){ 
        
            var container = document.querySelector('.fileInfoContainer');
            container.classList.remove('display-none');

            var file = this;
    
            var filename = file.dataset.filename;
            var folder = file.dataset.folder;
            var filetype = file.dataset.filetype;
            var filesize = file.dataset.filesize;
    
            var currentImage = document.getElementById('currentImage');
            var fileElement = document.getElementById('currentFile');
            var currentFilesize = document.getElementById('currentFilesize');
            var currentFiletype = document.getElementById('currentFiletype');
    
            var image = document.createElement('img');
            image.setAttribute('src', "/" + folder + '/' + filename)
    
            if(currentImage.children.length !== 0) {
    
                currentImage.children[0].remove();
            }
            
            currentImage.append(image);
            fileElement.innerText = "";
            fileElement.append("/" + folder + '/' + filename)
            currentFiletype.innerText = "";
            currentFiletype.append(filetype)
            currentFilesize.innerText = "";
            currentFilesize.append(parseFloat(filesize / 1000000).toFixed(2) + 'MB');
        }); 
    }
}