class Modal {

    constructor() {

        this.element;

        this.setElement();
        this.setEditOnclickEvent();
        this.setCreateOnclickEvent();
        this.setAddOnclickEvent();
    }

    setElement() {

        this.element = document.getElementById('modal');
    }

    getElement() {

        return this.element;
    }

    getHtmlElement() {

        return document.querySelector('html');
    }

    setCreateOnclickEvent() {

        var modal = this;

        document.getElementById('create').addEventListener("click", function() { 
                
            modal.showModal('Create', this); 
        }); 
    }

    setEditOnclickEvent() {

        var modal = this;
        var elements = document.querySelectorAll('.edit');

        for(var element of elements) {

            element.addEventListener("click", function() { 
                
                modal.showModal('Edit', this); 
            }); 
        }
    }

    setAddOnclickEvent() {

        var modal = this;
        var elements = document.querySelectorAll('.add');

        for(var element of elements) {

            element.addEventListener("click", function() { 
                
                modal.getElement().classList.remove('display-none');
                modal.getHtmlElement().classList.add('dark-layer');
            }); 
        }
    }

    setCloseOnclickEvent() {

        var modal = this;

        document.getElementById('close').addEventListener("click", function() { 
                
            modal.closeModal(modal); 
        }); 
    }

    closeModal(modal) {

        modal.getElement().classList.add('display-none');
        this.getHtmlElement().classList.remove('dark-layer');

        if(modal.getElement().children[0].children[0].children[0].nodeName === 'FORM') {

            modal.getElement().children[0].children[0].children[0].remove();
        }
    }


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

    createCreateForm() {

        var form = document.createElement('form');
        form.setAttribute('action', '/admin/categories/store');
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
        buttonContainer.append(containerAnchor)

        this.getElement().classList.add('modal-edit');
        this.getElement().children[0].children[0].append(form);

        this.setCloseOnclickEvent();
    }

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