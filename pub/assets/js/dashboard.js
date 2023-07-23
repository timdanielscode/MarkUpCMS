var navigationMenu = document.getElementById('navigationMenu');
var dropdownItems = navigationMenu.children[0];

for(var dropdownItem of dropdownItems.children) {

    if(dropdownItem.classList.contains('dropdown') === false) {

        dropdownItem.addEventListener('click', function() { 
            
            clearOpenDropdownMenu(dropdownItem.parentNode.children, this.nextElementSibling);
            this.nextElementSibling.classList.toggle('display-none');
        });
    }
}

function clearOpenDropdownMenu(elements, justOpenedElement) {

    for(var element of elements) {

        if(element.classList.contains('dropdown') === true && element.classList.contains('display-none') === false && element !== justOpenedElement) {

            element.classList.add('display-none')
        }
    }
}