var selected = "";
var startSelection = "";

document.addEventListener("selectionchange", e => {
    selection = document.getSelection().toString();
    selected = selection;

});

//var pTag = document.getElementById("pTag");
//console.log(pTag.value);

//pTag.addEventListener('click', clickHandler);

function clickHandler(tag) {
    console.log(tag);
    var start = "<"+tag+">";
    var end = "<"+tag+"/>";
    var addedTag = start + selected.concat(end);
    var body = document.getElementById("body");
    body.value = body.value.replace(selected,addedTag);
}



   