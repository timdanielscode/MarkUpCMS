var category = new Category();
var page = new Page();

if(category.getElement() !== null) {

    category.setOnclickEvents();
}

if(page.getElement() !== null) {

    page.setOnclickEvents();
}