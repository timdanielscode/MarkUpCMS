function toVisualBuilder() {

    //var body = document.getElementById('body');
    //body.remove();

    //var elem = document.getElementsByTagName('body')[0];
    //var target = elem.innerHTML;
    //elem.innerHTML = target.replace(/(<textarea)/igm, '<code').replace(/<\/textarea>/igm, '</code>');
    
    var body = document.getElementById('body');
    var builder = document.getElementById('builder');

    body.classList.add("display-none");
    builder.classList.add("display-block");

    body.classList.remove("display-block");
}

function toBuilder() {

    var body = document.getElementById('body');
    var builder = document.getElementById('builder');

    builder.classList.remove("display-block");
    body.classList.remove("display-none");

    body.classList.add("display-block");
}


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

    var start = "<"+tag+">";
    var end = "</"+tag+">";
    var addedTag = start + selected.concat(end);
    var body = document.getElementById("body");
    body.value = body.value.replace(selected,addedTag);
}


/* 
    dragable section 
*/

const fill = document.querySelector('.fill');
const empties = document.querySelectorAll('.empty');

fill.addEventListener('dragstart', dragStart);
fill.addEventListener('dragend', dragEnd);

for(const empty of empties) {
    empty.addEventListener('dragover', dragOver);
    empty.addEventListener('dragenter', dragEnter);
    empty.addEventListener('dragleave', dragLeave);
    empty.addEventListener('drop', dragDrop);
}

function dragStart() {
    this.className += ' hold';
    setTimeout(() => this.className = 'invisible', 0);
}

function dragEnd() {
    this.className = 'fill';

}
   
function dragOver(e) {
    e.preventDefault();
}

function dragEnter(e) {
    e.preventDefault();
    this.className += ' hovered';
}

function dragLeave() {
    this.className = 'empty';
}

function dragDrop() {
    this.className = 'empty';
    this.append(fill);
}


