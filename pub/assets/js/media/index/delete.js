$(document).ready(function() {

    var collection = [];

    $(document).on('click', '.deleteCheckbox', function() {

        document.querySelector('.indexDeleteForm').classList.remove('display-none-important')
        document.querySelector('.deleteSeparator').classList.remove('display-none')

        if(jQuery.inArray(this.value, collection) !== -1) {

            collection.splice($.inArray(this.value, collection), 1);
        } else {
            collection.push(this.value)
        }

        removeDeleteLink(collection);
        $("#deleteIds").val(collection)
    });

    function removeDeleteLink(collection) {

        if(collection.length === 0) {

            document.querySelector('.indexDeleteForm').classList.add('display-none-important')
            document.querySelector('.deleteSeparator').classList.add('display-none')
        }
    }
});