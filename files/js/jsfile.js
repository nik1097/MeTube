$(document).ready(function(){
    $(".hamburgermenu").on("click", function() { 
        var main = $("#main-section");
        var nav = $("#side-nav");
        if(main.hasClass("padding-left")) {
            nav.hide();
        }
        else {
            nav.show();
        }
        main.toggleClass("padding-left");
    });
});

