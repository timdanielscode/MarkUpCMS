class Sidebar {

    constructor() {

        this.openDropdownElements = [];

        for(var element of this.getDropdownItemElements()) {

            if(element.nextElementSibling !== null && element.nextElementSibling.classList.contains('display-none') === false) {

                element.classList.add('active')
            }
        }
    }

    /*
     * To get necessary elements
    */
    getDropdownItemElements() {

        return document.querySelectorAll('.dropdownItem');
    }

    setAllOpenDropdownElements() {

        for(var element of this.getDropdownItemElements() ) {

            if(element.nextElementSibling !== null && element.nextElementSibling.classList.contains('display-none') === false) {

                this.openDropdownElements.push(element)
            }
        }
    }

    /*
     * After clicking on 'dropdown item elements' to run the setAllOpenDropdownElements, clearOpenDropdownMenu, openDropdownMenu methods
     *
     * @param element object anchor tag (post button)
    */
    setOnclickEvents() {

        var sidebar = this;

        for(var element of this.getDropdownItemElements()) {

            if(element.classList.contains('dropdown') === false) {
        
                element.addEventListener('click', function() { 

                    sidebar.setAllOpenDropdownElements();
                    sidebar.clearOpenDropdownMenu(this);
                    sidebar.openDropdownMenu(this);
                });
            }
        }
    }

    /*
     * To open/close dropdown menus
     *
     * @param object element li tag
    */
    openDropdownMenu(element) {

        element.nextElementSibling.classList.toggle('display-none')

        if(element.classList.contains('nestedDropdownItem') === false) {

            element.classList.toggle('active')
        }
    }

    /*
     * To close open drop down menus if any dropdown menu is open and another dropdowm menu opens
     *
     * @param object element li tag
    */
    clearOpenDropdownMenu(element) {

        for(var openDropdownElement of this.openDropdownElements) {

            if(element !== openDropdownElement && element.classList.contains('nestedDropdownItem') === false) {

                openDropdownElement.nextElementSibling.classList.add('display-none')
                openDropdownElement.classList.remove('active')
            }
        }
    }
}
