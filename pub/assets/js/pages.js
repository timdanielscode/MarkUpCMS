var metaButton = document.getElementById('metaButton');
var categoryButton = document.getElementById('categoryButton');

if(metaButton !== null) {

    metaButton.addEventListener("click", function() { 
       
        var metaForm = document.getElementById('metaForm');
        metaForm.classList.toggle('display-none');
    }); 
}

if(categoryButton !== null) {

    categoryButton.addEventListener("click", function() { 
       
        var categorySection = document.getElementById('category');
        categorySection.classList.toggle('display-none');
    }); 
}