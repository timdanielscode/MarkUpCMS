class Modal {

    constructor() {

        this.setElement();
        this.setEditOnclickEvent();
        this.setCreateOnclickEvent();
        this.setDeleteOnclickEvent();
        
        if(this.element.dataset.id === "") {

            this.createCreateForm(this.element, true)
            this.getElement().classList.remove('display-none');
            this.getHtmlElement().classList.add('dark-layer');
        }
    }

    /*
     * To get necessary elements
    */
    setElement() {

        this.element = document.getElementById('modal');
    }

    getElement() {

        return this.element;
    }

    getHtmlElement() {

        return document.querySelector('html');
    }

    getEditButtonElement() {

        return document.querySelector('.edit');
    }

    getCreateButtonElement() {

        return document.getElementById('create');
    }

    getDeleteButtonElement() {

        return document.getElementById('delete');
    }

    getTableElement() {

        return document.querySelector('table');
    }

    getDeleteFormElement() {

        return document.querySelector('.deleteForm');
    }

    /*
     * After clicking on the 'create button' to run the showModal method
    */
    setCreateOnclickEvent() {

        var modal = this;
        
        this.getCreateButtonElement().addEventListener("click", function() { 
                
            if(modal.getElement().classList.contains('display-none') === true) {

                modal.showModal('Create', this); 
            }
        }); 
    }

    /*
     * After clicking on the 'edit button' to run the showModal method
    */
    setEditOnclickEvent() {

        var modal = this;

        this.getEditButtonElement().addEventListener("click", function() { 

            if(modal.getElement().classList.contains('display-none') === true) {
                
                modal.showModal('Edit', this); 
            }
        }); 
    }

    /*
     * After clicking on the 'delete button' to run the changeListedCategories method
    */
    setDeleteOnclickEvent() {

        this.getDeleteButtonElement().addEventListener("click", function() { 
                
            modal.changeListedCategories(modal.getDeleteButtonElement());
        }); 
    }

    /*
     * After clicking on the 'close button' to run the closeModal method
    */
    setCloseOnclickEvent() {

        var modal = this;

        document.getElementById('close').addEventListener("click", function() { 
                
            modal.closeModal(); 
        }); 
    }

    /*
     * To close the modal
    */
    closeModal() {

        this.getElement().classList.add('display-none');
        this.getHtmlElement().classList.remove('dark-layer');

        if(this.getElement().children[0].children[0].children[0].nodeName === 'FORM') {

            this.getElement().children[0].children[0].children[0].remove();
        }
    }

    /*
     * To change the categories list to create a selection to submit and delete categoriees
     *
     * @param object element anchor tag (delete button)
    */
    changeListedCategories(element) {

        this.getTableElement().remove();
        this.getDeleteFormElement().classList.remove('display-none')

        element.remove();
    }

    /*
     * To create the edit form to submit and update the category title and description
     *
     * @param object element anchor tag (edit button)
    */
    createEditForm(element) {

        var form = document.createElement('form');
        form.setAttribute('action', '/admin/categories/update');
        form.setAttribute('method', 'POST');

        var labelTitle = document.createElement('label');
        var labelTitleText = document.createTextNode("Title:");
        labelTitle.appendChild(labelTitleText);

        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('name', 'title');
        input.setAttribute('palaceholder', 'Title');
        input.setAttribute('value', element.getAttribute('data-title'));

        var labelDescription = document.createElement('label');
        var labelDescriptionText = document.createTextNode('Description:');
        labelDescription.appendChild(labelDescriptionText);

        var textarea = document.createElement('textarea');
        textarea.setAttribute('type', 'text');
        textarea.setAttribute('name', 'description');
        textarea.setAttribute('palaceholder', 'Description');
        textarea.appendChild(document.createTextNode(element.getAttribute('data-description')));
     
        var buttonContainer = document.createElement('div');
        buttonContainer.classList.add('buttonContainer');
        buttonContainer.classList.add('margin-t-20');
        
        var containerInput = document.createElement('input');
        containerInput.setAttribute('type', 'submit');
        containerInput.setAttribute('name', 'submit');
        containerInput.setAttribute('value', 'Update');
        containerInput.classList.add('button');
        containerInput.classList.add('greenButton');
        containerInput.classList.add('margin-t-20');
        containerInput.classList.add('margin-r-20');

        var containerHiddenInput = document.createElement('input');
        containerHiddenInput.setAttribute('type', 'hidden');
        containerHiddenInput.setAttribute('name', 'id');
        containerHiddenInput.setAttribute('value', element.getAttribute('data-id'));

        var containerAnchor = document.createElement('a');
        containerAnchor.setAttribute('id', 'close');
        containerAnchor.classList.add('button');
        containerAnchor.classList.add('blueButton');
        containerAnchor.classList.add('margin-t-20');
        containerAnchor.classList.add('box-sizing-border-box');
        containerAnchor.appendChild(document.createTextNode('Close'));

        form.append(labelTitle);
        form.append(input);
        form.append(labelDescription);
        form.append(textarea);
        form.append(buttonContainer);
        buttonContainer.append(containerInput);
        buttonContainer.append(containerHiddenInput);
        buttonContainer.append(containerAnchor);

        this.getElement().classList.add('modal-edit');
        this.getElement().children[0].children[0].append(form);

        this.setCloseOnclickEvent();
    }

    /*
     * To create the create form to submit to store a new category
     *
     * @param object element anchor tag (create button)
     * @param bool notExists   
    */
    createCreateForm(element, notExists = null) {

        var form = document.createElement('form');
        form.setAttribute('action', '/admin/categories/' + element.dataset.id + '/store');
        form.setAttribute('method', 'POST');

        var labelTitle = document.createElement('label');
        labelTitle.appendChild(document.createTextNode("Title:"));

        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('name', 'title');
        input.setAttribute('palaceholder', 'Title');
        input.setAttribute('autofocus', '');

        var labelDescription = document.createElement('label');
        labelDescription.appendChild(document.createTextNode('Description:'));

        var textarea = document.createElement('textarea');
        textarea.setAttribute('type', 'text');
        textarea.setAttribute('name', 'description');
        textarea.setAttribute('palaceholder', 'Description');

        var buttonContainer = document.createElement('div');
        buttonContainer.classList.add('buttonContainer');
        buttonContainer.classList.add('margin-t-20');

        var containerInput = document.createElement('input');
        containerInput.setAttribute('type', 'submit');
        containerInput.setAttribute('name', 'submit');
        containerInput.setAttribute('value', 'Store');
        containerInput.classList.add('button');
        containerInput.classList.add('greenButton');
        containerInput.classList.add('margin-t-20');
        containerInput.classList.add('margin-r-20');

        var containerAnchor = document.createElement('a');
        containerAnchor.setAttribute('id', 'close');
        containerAnchor.classList.add('button');
        containerAnchor.classList.add('blueButton');
        containerAnchor.classList.add('margin-t-20');
        containerAnchor.classList.add('box-sizing-border-box');
        containerAnchor.appendChild(document.createTextNode('Close'));
        
        form.append(labelTitle);
        form.append(input);
        form.append(labelDescription);
        form.append(textarea)
        form.append(buttonContainer);
        buttonContainer.append(containerInput);

        if(notExists !== true) {

            buttonContainer.append(containerAnchor);
        }

        this.getElement().classList.add('modal-edit');
        this.getElement().children[0].children[0].append(form);

        if(notExists !== true) {

            this.setCloseOnclickEvent();
        }
    }

    /*
     * To show the modal to submit and store or update categories
     *
     * @param string type Create | Edit
     * @param object anchor tag (create | edit button)
    */
    showModal(type, dataElement) {

        this.getElement().classList.remove('display-none');
        this.getHtmlElement().classList.add('dark-layer');

        if(type === 'Create') {

            this.createCreateForm(dataElement);

        } else if(type === 'Edit') {

            this.createEditForm(dataElement);
        } 
    }
}