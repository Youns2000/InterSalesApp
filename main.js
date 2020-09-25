$(document).ready(function() {
    $("li a").click(function(event) {
        page = $(this).attr("href");
        $.ajax({
                method: "POST",
                url: "pages/"+page,
                data: {}
            }).done(function(res) {
                afficher(res);
            });
        return false;
    });

    function afficher(data) {
        $(".article").fadeOut('500', function() {
            $(".article").empty();
            $(".article").append(data);
            $(".article").fadeIn('500');
        });
    }

    $( window ).load(function() {
         $.ajax({
            method: "POST",
            url: "pages/accueil.html",
            data: {}
        }).done(function(res) {
            $(".article").empty();
            $(".article").append(res);
        });
        return false;
    });
});
