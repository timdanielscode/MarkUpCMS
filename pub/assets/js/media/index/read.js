$(document).ready(function() {

    $(document).on('click', '.mediaRead', function() {

        var readImageContainer = $("#MEDIAREAD");

        if(this.children[0].classList.contains('pdfImage')) {

            var file = $("<iframe>");
            file.attr("src", this.children[0].getAttribute('data-src'))
            file.attr("id", "mediaReadPdf")
            file.addClass('readFile');

        } else if(this.children[0].classList.contains('image')) {

            var file = $("<img>");
            file.attr("src", this.children[0].getAttribute('src'))
            file.attr("id", "mediaReadImage")
            file.addClass('readFile');
        } else if(this.children[0].classList.contains('video')) {

            var file = $("<video>");
            file.attr("src", this.children[0].getAttribute('src'))
            file.attr("id", "mediaReadVideo")
            file.attr("controls", "true")
            file.addClass('readFile');
        }

        readImageContainer.append(file)
        $("html").addClass('dark-layer')
    });

    $(document).on('click', '.readFile', function() {

        this.remove();
        $("html").removeClass('dark-layer')
    });

    $(document).on('click', '.dark-layer', function() {

        var element = document.querySelector('.readFile');

        element.remove();
        $("html").removeClass('dark-layer')
    });
});