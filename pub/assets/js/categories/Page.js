class Page {

    constructor() {

        this.pageIds = [];
    }

    /*
     * To get necessary elements
    */
    getElement() {

        return document.getElementById('page');
    }

    getPageIdsElement() {

        return document.getElementById('pageIds');
    }

    /*
     * After clicking on a 'page element' to run the selectedPages method
    */
    setOnclickEvents() {

        var page = this;

        for(var element of this.getElement().children) {

            if(element.nodeName === 'SELECT') {

                var selectElement = element;

                for(var element of selectElement.children) {

                    element.addEventListener("click", function() { 
                
                        page.selectedPages(this);
                    }); 
                }
            }
        }
    }

    /*
     * To show a page is selected, to push selected page id to submit the selection to 'apply'
     *
     * @param object element option tag
    */
    selectedPages(element) {

        element.classList.toggle('selectedCategory');

        if(this.pageIds.includes(element.value) === false) {

            this.pageIds.push(element.value);
        } else {

            var indexElementValue = this.pageIds.indexOf(element.value);

            if (indexElementValue !== -1) {

                this.pageIds.splice(indexElementValue, 1);
            }
        }

        this.getPageIdsElement().value = this.pageIds;
    }
}