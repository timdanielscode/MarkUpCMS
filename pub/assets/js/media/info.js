var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {

    for(var fileContainer of fileContainers) {

        fileContainer.children[0].addEventListener("click", function(){ 
        
            var container = document.querySelector('.fileInfoContainer');
            container.classList.remove('display-none');

            var filename = this.dataset.filename;
            var folder = this.dataset.folder;
            var filetype = this.dataset.filetype;
            var filesize = this.dataset.filesize;
    
            var currentImage = document.getElementById('currentImage');
            var fileElement = document.getElementById('currentFile');
            var currentFilesize = document.getElementById('currentFilesize');
            var currentFiletype = document.getElementById('currentFiletype');
    
            var image = document.createElement('img');
            image.setAttribute('src', "/" + folder + '/' + filename)

            currentImage.style.height = currentImage.clientWidth + 'px';
    
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

var currentImage = document.getElementById('currentImage');

window.addEventListener("resize", function() {

    currentImage.style.height = currentImage.clientWidth + 'px';
}); 