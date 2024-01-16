class Category {

    constructor() {

        this.categoryIds = [];
    }

    /*
     * To get necessary elements
    */
    getElement() {

        return document.getElementById('category');
    }

    getCategoryIdsElement() {

        return document.getElementById('categoryIds');
    }

    /*
     * After clicking on a 'category element' to run the selectedCategories method
    */
    setOnclickEvents() {

        var category = this;

        for(var element of this.getElement().children) {

            if(element.nodeName === 'SELECT') {

                var selectElement = element;

                for(var element of selectElement.children) {

                    element.addEventListener("click", function() { 
                
                        category.selectedCategories(this);
                    }); 
                }
            }
        }
    }

    /*
     * To show a category is selected, to push selected category id to submit the selection to 'apply'
     *
     * @param object element option tag
    */
    selectedCategories(element) {

        element.classList.toggle('selectedCategory');

        if(this.categoryIds.includes(element.value) === false) {

            this.categoryIds.push(element.value);
        } else {

            var indexElementValue = this.categoryIds.indexOf(element.value);

            if (indexElementValue !== -1) {

                this.categoryIds.splice(indexElementValue, 1);
            }
        }

        this.getCategoryIdsElement().value = this.categoryIds;
    }
}