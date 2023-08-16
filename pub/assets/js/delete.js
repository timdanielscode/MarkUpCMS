var elements = document.querySelectorAll('.deleteCheckbox');

var collection = [];

for(var element of elements) {

    element.addEventListener("click", function() {
    
        if(collection.includes(this.value) === true) {
            
            collection.splice(collection.indexOf(this.value), 1);
        } else {
            collection.push(this.value)
        }

        document.getElementById('deleteIds').value = collection;
    });
}
