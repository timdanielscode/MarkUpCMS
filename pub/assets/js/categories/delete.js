$(document).ready(function() {

    var collection = [];

    $(document).on('click', '.deleteCheckbox', function() {

        if(jQuery.inArray(this.value, collection) !== -1) {

            collection.splice($.inArray(this.value, collection), 1);
        } else {
            collection.push(this.value)
        }

        $("#deleteIds").val(collection)
    });
});