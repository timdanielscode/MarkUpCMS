var elements = document.querySelectorAll('.deleteCheckbox');

var recoverCollection = [];

for(var element of elements) {

    element.addEventListener("click", function() {
    
        if(recoverCollection.includes(this.value) === true) {
            
            recoverCollection.splice(recoverCollection.indexOf(this.value), 1);
        } else {
            recoverCollection.push(this.value)
        }

        document.getElementById('recoverIds').value = recoverCollection;
    });
}
