class Navbar {

    constructor() {

        this.element;
        this.setElement();
    }

    setElement() {

        var element = document.getElementById('profileIcon');

        if(element !== null && typeof element !== 'undefined') {

            this.element = element;
        }
    }

    getElement() {

        return this.element;
    }

    setOnclickEvent(progressInfoItem) {

        this.getElement().onclick = function(){
            
            showMenu(progressInfoItem);
        };
    }

}

function showMenu() {

    var body = document.querySelector('html');
    body.classList.toggle('dark-layer');
    var profileDropdown = document.getElementById('profileDropdown');
    profileDropdown.classList.toggle('show-dropdown')
}