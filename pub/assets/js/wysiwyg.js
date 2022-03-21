
var selected = "";
var startSelection = "";

document.addEventListener("selectionchange", e => {
    selection = document.getSelection().toString();
    selected = selection;

});

function clickHandler(tag) {

    var start = "<"+tag+">";
    var end = "</"+tag+">";
    var addedTag = start + selected.concat(end);
    var body = document.getElementById("body");
    body.value = body.value.replace(selected,addedTag);
}
