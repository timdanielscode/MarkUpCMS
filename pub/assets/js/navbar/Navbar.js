class Navbar {

    /*
     * To get necessary elements
    */
    getElement() {

        return document.getElementById('profileIcon');
    }

    getBodyElement() {

        return document.querySelector('html');
    }

    getDropdownElement() {

        return document.getElementById('profileDropdown');
    }

    setOnclickEvent() {

        var navbar = this;

        this.getElement().onclick = function() {
            
            navbar.showHideMenu();
        };
    }

    /*
     * To show/hide the navbar profile menu afger clicking on the 'profile icon'
    */
    showHideMenu() {

        this.getBodyElement().classList.toggle('dark-layer');
        this.getDropdownElement().classList.toggle('show-dropdown')
    }
}

