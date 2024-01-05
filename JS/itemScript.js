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

var team_slider = new Swiper(".pro-slider", {
    slidesPerView: 4,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    speed: 2000,

    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        0: {
            slidesPerView: 1.2,
        },
        768: {
            slidesPerView: 2,
        },
        992: {
            slidesPerView: 3,
        },
        1200: {
            slidesPerView: 4,
        },
    },
});

jQuery(window).on('load', function () {
    $('body').removeClass('body-fixed');

});

// Mô tả sản phẩm
const moTaHD = document.querySelectorAll(".moTaSP-HDMH .moTaSP div"),
    mota = document.querySelector(".moTa"),
    HD = document.querySelector(".HD");

moTaHD.forEach(function (moTaHDDiv) {
    moTaHDDiv.addEventListener("click", function () {
        removedActive();
        moTaHDDiv.classList.add("active1");
    });
});

function removedActive() {
    let activeMoTaSP = document.querySelector('.active1');
    activeMoTaSP.classList.remove("active1");
}

const moTaHD_text1 = document.querySelector(".M-H-text-1"),
    moTaHD_text2 = document.querySelector(".M-H-text-2");

mota.addEventListener('click', function () {
    removeMotaHD_Text();
    moTaHD_text1.classList.add("active2");
});

HD.addEventListener('click', function () {
    removeMotaHD_Text();
    moTaHD_text2.classList.add("active2");
});

function removeMotaHD_Text() {
    let moTaHD_textMain = document.querySelector('.active2');
    moTaHD_textMain.classList.remove("active2");
}



// Tìm kiếm
const addSearch = document.querySelector('.header-btn.heart'),
    deleteSearch = document.querySelector('.uil.uil-times'),
    searchBox = document.querySelector('.search'),
    search = document.querySelector('.search-container');

addSearch.addEventListener('click', function () {
    search.classList.add("active");
    searchBox.classList.add("active");
});

deleteSearch.addEventListener('click', function () {
    search.classList.remove("active");
    searchBox.classList.remove("active");
});

// Tìm kiếm
$(document).ready(function () {

    $("#live_search").keyup(function () {

        var input = $(this).val();
        // alert(input);

        if (input != "") {
            $.ajax({

                url: "../dashboard/searchContent.php",
                method: "POST",
                data: { input: input },

                success: function (data) {
                    $("#searchResult").html(data);
                }

            });
            $("#searchResult").css("display", "block");
        }
        else {
            $("#searchResult").css("display", "none");
        }

    });

});