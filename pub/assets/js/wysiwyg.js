var selected = "";

document.addEventListener("selectionchange", e => {
    selection = document.getSelection().toString();
    selected = selection;
})

var pTag = document.getElementById("pTag");

pTag.addEventListener('click', clickHandler);

function clickHandler() {
    console.log(selected);
}


   