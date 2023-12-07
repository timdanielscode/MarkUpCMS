class Category {

    constructor() {

        this.element;
        this.categoryIds = [];

        this.setElement();
    }

    setElement() {

        this.element = document.getElementById('category');
    }

    getElement() {

        return this.element;
    }

    setOnclickEvents() {

        var category = this;

        for(var element of this.getElement()) {

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

    selectedCategories(element) {

        element.classList.toggle('selectedCategory');

        var categoryIds = document.getElementById('categoryIds');
        
        if(this.categoryIds.includes(element.value) === false) {

            this.categoryIds.push(element.value);
        } else {

            var indexElementValue = this.categoryIds.indexOf(element.value);

            if (indexElementValue !== -1) {

                this.categoryIds.splice(indexElementValue, 1);
            }
        }

        categoryIds.value = this.categoryIds;
    }
}