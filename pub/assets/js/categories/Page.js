class Page {

    constructor() {

        this.element;
        this.pageIds = [];

        this.setElement();
    }

    setElement() {

        this.element = document.getElementById('page');
    }

    getElement() {

        return this.element;
    }

    getPageIdsElement() {

        return document.getElementById('pageIds');
    }

    setOnclickEvents() {

        var page = this;

        for(var element of this.getElement()) {

            if(element.nodeName === 'SELECT') {

                var selectElement = element;

                for(var element of selectElement.children) {

                    element.addEventListener("click", function() { 
                
                        page.selectedCategories(this);
                    }); 
                }
            }
        }
    }

    selectedCategories(element) {

        element.classList.toggle('selectedCategory');

        var pageIds = this.getPageIdsElement();
        
        if(this.pageIds.includes(element.value) === false) {

            this.pageIds.push(element.value);
        } else {

            var indexElementValue = this.pageIds.indexOf(element.value);

            if (indexElementValue !== -1) {

                this.pageIds.splice(indexElementValue, 1);
            }
        }

        pageIds.value = this.pageIds;
    }

}