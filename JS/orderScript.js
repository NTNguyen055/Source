$(document).ready(function ($) {
    "use strict";

    /**
     * PRELOAD
     * 
     * loading will be end after document is loaded
     */

    const preloader = document.querySelector("[data-preaload]");

    window.addEventListener("load", function () {
        preloader.classList.add("loaded");
        document.body.classList.add("loaded");

    });

    document.querySelector(".scrolltop").addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    jQuery(".menu-toggle").click(function () {
        jQuery(".main-navigation").toggleClass("toggled");
    });

    jQuery(".header-menu ul li a").click(function () {
        jQuery(".main-navigation").removeClass("toggled");
    });


    jQuery(".header-btn.user").click(function () {
        jQuery(".user-box").toggleClass("toggled");
    });

    jQuery(".header-btn.user.toggled").click(function () {
        jQuery(".user-box").removeClass("toggled");
    });

    gsap.registerPlugin(ScrollTrigger);

    var elementFirst = document.querySelector('.site-header');
    ScrollTrigger.create({
        trigger: "body",
        start: "30px top",
        end: "bottom bottom",

        onEnter: () => myFunction(),
        onLeaveBack: () => myFunction(),
    });

    function myFunction() {
        elementFirst.classList.toggle('sticky_head');
    }

    var scene = $(".js-parallax-scene").get(0);
    var parallaxInstance = new Parallax(scene);
});


jQuery(window).on('load', function () {
    $('body').removeClass('body-fixed');
});

$(document).ready(function () {
    // Listen for changes in the "province" select box
    $('#province').on('change', function () {
        var province_id = $(this).val();
        console.log(province_id);
        if (province_id) {
            // If a province is selected, fetch the districts for that province using AJAX
            $.ajax({
                url: '../Ajax/ajax_get_district.php',
                method: 'GET',
                dataType: "json",
                data: {
                    province_id: province_id
                },
                success: function (data) {
                    // Clear the current options in the "district" select box
                    $('#district').empty();

                    // Add the new options for the districts for the selected province
                    $.each(data, function (i, district) {
                        // console.log(district);
                        $('#district').append($('<option>', {
                            value: district.id,
                            text: district.name
                        }));
                    });
                    // Clear the options in the "wards" select box
                    $('#wards').empty();
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Error: ' + errorThrown);
                }
            });
            $('#wards').empty();
        } else {
            // If no province is selected, clear the options in the "district" and "wards" select boxes
            $('#district').empty();
        }
    });

    // Listen for changes in the "district" select box
    $('#district').on('change', function () {
        var district_id = $(this).val();
        console.log(district_id);
        if (district_id) {
            // If a district is selected, fetch the awards for that district using AJAX
            $.ajax({
                url: '../Ajax/ajax_get_wards.php',
                method: 'GET',
                dataType: "json",
                data: {
                    district_id: district_id
                },
                success: function (data) {
                    console.log(data);
                    // Clear the current options in the "wards" select box
                    $('#wards').empty();
                    // Add the new options for the awards for the selected district
                    $.each(data, function (i, wards) {
                        $('#wards').append($('<option>', {
                            value: wards.id,
                            text: wards.name
                        }));
                    });
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Error: ' + errorThrown);
                }
            });
        } else {
            // If no district is selected, clear the options in the "award" select box
            $('#wards').empty();
        }
    });
});
