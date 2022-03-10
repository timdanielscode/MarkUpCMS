var selected = "";
var startSelection = "";

document.addEventListener("selectionchange", e => {
    selection = document.getSelection().toString();
    selected = selection;
});

var pTag = document.getElementById("pTag");

pTag.addEventListener('click', clickHandler);

function clickHandler() {

    var startP = "<p>";
    var endP = "</p>";
    var addedP = startP + selected + endP;
    var body = document.getElementById("body");
    body.value = body.value.replace(selection,addedP);
}



   