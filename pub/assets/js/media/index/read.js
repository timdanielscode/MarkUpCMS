$(document).ready(function() {

    $(document).on('click', '.mediaRead', function() {

        var readImageContainer = $("#MEDIAREAD");
        var image = $("<img>");
        image.attr("src", this.children[0].getAttribute('src'))
        image.attr("id", "mediaReadImage")
        readImageContainer.append(image)
        $("html").addClass('dark-layer')
    });
});

$(document).ready(function() {

    $(document).on('click', '#mediaReadImage', function() {

        this.remove();
        $("html").removeClass('dark-layer')
    });
});