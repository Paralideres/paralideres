$(document).ready(function(){
    /*
     =========================================================================================
     12. SLIDE
     =========================================================================================
     */

    // var banner_slider = $("#banner_slider");
    // banner_slider.owlCarousel({
    //     loop: true,
    //     margin: 0,
    //     smartSpeed:450,
    //     autoplay:false,
    //     dots:true,
    //     nav: false,
    //     responsive: {
    //         0: {
    //             items: 1
    //         },
    //         400: {
    //             items: 1
    //         },
    //         768: {
    //             items: 1
    //         },
    //         1200: {
    //             items: 1
    //         }
    //     }
    // });

    var test_slider = $("#test_slider");
    test_slider.owlCarousel({
        loop: true,
        margin: 0,
        smartSpeed:450,
        autoplay:false,
        dots:true,
        nav: false,
        responsive: {
            0: {
                items: 1
            },
            400: {
                items: 1
            },
            768: {
                items: 1
            },
            1200: {
                items: 1
            }
        }
    });

    $('.match_item').matchHeight({
        byRow: true,
        property: 'height',
        target: null,
        remove: false
    });

    $('a[data-toggle="tooltip"]').tooltip({
        animated: 'fade',
        placement: 'top',
        html: true
    });

    $(".open_login").click(function() {
        $(".popup_content").addClass("open_content");
    });
    $(".cross_ic").click(function() {
        $(".popup_content").removeClass("open_content");
        $(".step_1 ,.step_2 ,.step_3, .step_4").hide();
    });
    $(".open_login").click(function() {
        return false;
    });
    $(".open_login").click(function() {
        $(".step_1").show();
    });
    // $(".step_1 .resource_2").click(function() {
    //     $(".step_1").hide();
    //     $(".step_2").show();
    //     return false;
    // });
    // $(".step_2 .resource_2").click(function() {
    //     $(".step_2").hide();
    //     $(".step_3").show();
    //     return false;
    // });
    // $(".step_3 .resource_2").click(function() {
    //     $(".step_3").hide();
    //     $(".step_4").show();
    //     return false;
    // });
    // $(".resource_1").click(function() {
    //     $(".popup_content").removeClass("open_content");
    //     $(".step_1 ,.step_2 ,.step_3, .step_4").hide();
    //     return false;
    // });

});
