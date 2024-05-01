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

    getLogoElement() {

        return document.getElementById('logo');
    }

    setOnclickEvent() {

        var navbar = this;

        this.getElement().onclick = function() {
            
            navbar.showHideMenu();
        };
    }

    /*
     * To show/hide the navbar profile menu after clicking on the 'profile icon'
    */
    showHideMenu() {

        this.getBodyElement().classList.toggle('dark-layer');
        this.getDropdownElement().classList.toggle('show-dropdown')
    }

    /*
     * To adjust spacing in navbar mainly on edit and create views
    */
    adjustLogoSpacing() {

        if(this.getLogoElement() !== null && document.getElementById('sidebar') !== null && document.getElementById('ranger') === null) {

            this.getLogoElement().classList.add('lessSpacing');
        } else if(document.getElementById('ranger') !== null) {
            this.getLogoElement().classList.add('lesserSpacing');
        }
    }
}

