var dropdownItems = document.querySelectorAll('.dropdownItem');

for(var dropdownItem of dropdownItems) {

    if(dropdownItem.classList.contains('dropdown') === false) {

        dropdownItem.addEventListener('click', function() { 
                
            clearOpenDropdownMenu(this, dropdownItem.parentNode.children, this.nextElementSibling);
            this.nextElementSibling.classList.toggle('display-none')
        });
    }
}

function clearOpenDropdownMenu(dropdownItem, elements, justOpenedElement) {

    for(var element of elements) {

        if(element.classList.contains('dropdown') === true && element.classList.contains('display-none') === false && element !== justOpenedElement && dropdownItem.parentNode.classList.contains('dropdown') === false) {

            element.classList.add('display-none')
        }
    }
}