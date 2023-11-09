var metaButton = document.getElementById('metaButton');
var categoryButton = document.getElementById('categoryButton');
var cssButton = document.getElementById('cssButton');
var jsButton = document.getElementById('jsButton');
var widgetButton = document.getElementById('widgetButton');
var cdnbutton = document.getElementById('cdnButton');
var slugButton = document.getElementById('slugButton');

var metaForm = document.getElementById('metaForm');
var categorySection = document.getElementById('category');
var linkedCssSection = document.getElementById('linkedCssFiles');
var cssSection = document.getElementById('cssFiles');
var jsSection = document.getElementById('jsFiles');
var linkedJsSection = document.getElementById('linkedJsFiles');
var widgetSection = document.getElementById('widget');
var cdnSection = document.getElementById('cdn');
var slugSection = document.getElementById('slug');

if(slugButton !== null) {

    slugButton.addEventListener("click", function() { 

        cssSection.classList.add('display-none');
        linkedCssSection.classList.add('display-none');
        categorySection.classList.add('display-none');
        metaForm.classList.add('display-none');
        widgetSection.classList.add('display-none');
        cdnSection.classList.add('display-none');
        jsSection.classList.add('display-none');
        linkedJsSection.classList.add('display-none');

        slugSection.classList.toggle('display-none');
    }); 
}

if(jsButton !== null) {

    jsButton.addEventListener("click", function() { 

        cssSection.classList.add('display-none');
        linkedCssSection.classList.add('display-none');
        categorySection.classList.add('display-none');
        metaForm.classList.add('display-none');
        widgetSection.classList.add('display-none');
        cdnSection.classList.add('display-none');
        slugSection.classList.add('display-none');

        jsSection.classList.toggle('display-none');
        linkedJsSection.classList.toggle('display-none');
    }); 
}

if(metaButton !== null) {

    metaButton.addEventListener("click", function() { 

        cssSection.classList.add('display-none');
        linkedCssSection.classList.add('display-none');
        categorySection.classList.add('display-none');
        jsSection.classList.add('display-none');
        linkedJsSection.classList.add('display-none');
        widgetSection.classList.add('display-none');
        cdnSection.classList.add('display-none');
        slugSection.classList.add('display-none');

        metaForm.classList.toggle('display-none');
    }); 
}

if(categoryButton !== null) {

    categoryButton.addEventListener("click", function() { 

        cssSection.classList.add('display-none');
        linkedCssSection.classList.add('display-none');
        metaForm.classList.add('display-none');
        jsSection.classList.add('display-none');
        linkedJsSection.classList.add('display-none');
        widgetSection.classList.add('display-none');
        cdnSection.classList.add('display-none');
        slugSection.classList.add('display-none');
        
        categorySection.classList.toggle('display-none');
    }); 
}

if(cssButton !== null) {

    cssButton.addEventListener("click", function() { 
       
        categorySection.classList.add('display-none');
        metaForm.classList.add('display-none');
        jsSection.classList.add('display-none');
        linkedJsSection.classList.add('display-none');
        widgetSection.classList.add('display-none');
        cdnSection.classList.add('display-none');
        slugSection.classList.add('display-none');

        linkedCssSection.classList.toggle('display-none');
        cssSection.classList.toggle('display-none');
    }); 
}

if(widgetButton !== null) {

    widgetButton.addEventListener("click", function() { 
       
        categorySection.classList.add('display-none');
        metaForm.classList.add('display-none');
        jsSection.classList.add('display-none');
        linkedJsSection.classList.add('display-none');
        linkedCssSection.classList.add('display-none');
        cssSection.classList.add('display-none');
        cdnSection.classList.add('display-none');
        slugSection.classList.add('display-none');

        widgetSection.classList.toggle('display-none');
    }); 
}

if(cdnButton !== null) {

    cdnButton.addEventListener("click", function() {

        categorySection.classList.add('display-none');
        metaForm.classList.add('display-none');
        jsSection.classList.add('display-none');
        linkedJsSection.classList.add('display-none');
        linkedCssSection.classList.add('display-none');
        cssSection.classList.add('display-none');
        widgetSection.classList.add('display-none');
        slugSection.classList.add('display-none');

        cdnSection.classList.toggle('display-none');
    });
}