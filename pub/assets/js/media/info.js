var fileContainers = document.querySelectorAll('.fileContainer');

if(fileContainers !== null) {

    for(var fileContainer of fileContainers) {

        fileContainer.children[0].addEventListener("click", function(){ 
    
            var container = document.querySelector('.fileInfoContainer');
            container.classList.remove('display-none');

            if(this.classList.contains('iframeLayer')) {
                
                element = this.nextElementSibling;
            } else {
                element = this;
            }

            var fileId = element.dataset.id;
            var filename = element.dataset.filename;
            var folder = element.dataset.folder;
            var filetype = element.dataset.filetype;
            var filesize = element.dataset.filesize;
    
            var currentFile = document.getElementById('currentFile');
            var fileElement = document.getElementById('currentFolderFilename');
            var currentFilesize = document.getElementById('currentFilesize');
            var currentFiletype = document.getElementById('currentFiletype');
            var currentFilename = document.getElementById('currentFilename');
            var currentFolder = document.getElementById('currentFolder');
            var updateButton = document.getElementById('update');

            switch (filetype) {
                case 'image/png':
                   nodetype = 'img';
                break;
                case 'image/webp':
                    nodetype ='img';
                break;
                case 'image/gif':
                    nodetype = 'img';
                break;
                case 'image/jpeg':
                    nodetype = 'img';
                break;
                case 'image/svg+xml':
                    nodetype = 'img';
                break;
                case 'video/mp4':
                    nodetype = 'video';
                break;
                case 'video/quicktime':
                    nodetype = 'video';
                break;
                case 'application/pdf':
                    nodetype = 'iframe';
                    console.log('test')
                break;
                default:
                    nodetype = 'img';
              }

            var node = document.createElement(nodetype);
            node.setAttribute('src', "/" + folder + '/' + filename);

            if(nodetype === 'video') {
                node.setAttribute('controls', true);
            }

            currentFile.style.height = currentFile.clientWidth + 'px';
    
            if(currentFile.children.length !== 0) {
    
                currentFile.children[0].remove();
            }
            
            currentFile.append(node);
            fileElement.innerText = "";
            fileElement.append("/" + folder + '/' + filename)
            currentFilename.value = filename;
            currentFolder.setAttribute('data-folder', folder);
            updateButton.setAttribute('data-id', fileId);
            currentFiletype.innerText = "";
            currentFiletype.append(filetype)
            currentFilesize.innerText = "";
            currentFilesize.append(parseFloat(filesize / 1000000).toFixed(2) + 'MB');
        }); 
    }
}

var currentFile = document.getElementById('currentFile');

window.addEventListener("resize", function() {

    currentFile.style.height = currentFile.clientWidth + 'px';
}); 