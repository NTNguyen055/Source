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

  var team_slider = new Swiper(".pro-slider", {
    slidesPerView: 4,
    spaceBetween: 20,
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

//Menu sản phẩm
const danhMuc = document.querySelectorAll(".all-pro .pro-dm div"),

  tatCa = document.querySelector(".col-lg-9.menuAll"),
  khaiVi = document.querySelector(".col-lg-9.menu-khaivi"),
  sang = document.querySelector(".col-lg-9.menu-sang"),
  trua = document.querySelector(".col-lg-9.menu-trua"),
  toi = document.querySelector(".col-lg-9.menu-toi"),
  lau = document.querySelector(".col-lg-9.menu-lau"),
  nuong = document.querySelector(".col-lg-9.menu-nuong");

danhMuc.forEach(function (danhMucDiv) {
  danhMucDiv.addEventListener("click", function () {
    removedChosed();
    danhMucDiv.classList.add("chose");
  });
});

function removedChosed() {
  let chose = document.querySelector('.chose');
  chose.classList.remove("chose");
}


const chose1 = document.querySelector(".tatCa"),
  chose2 = document.querySelector(".khaiVi"),
  chose3 = document.querySelector(".sang"),
  chose4 = document.querySelector(".trua"),
  chose5 = document.querySelector(".toi"),
  chose6 = document.querySelector(".lau"),
  chose7 = document.querySelector(".nuong");



chose1.addEventListener('click', function () {
  removeMenuMain();
  tatCa.classList.add("menu-main");
});

chose2.addEventListener('click', function () {
  removeMenuMain();
  khaiVi.classList.add("menu-main");
});

chose3.addEventListener('click', function () {
  removeMenuMain();
  sang.classList.add("menu-main");
});

chose4.addEventListener('click', function () {
  removeMenuMain();
  trua.classList.add("menu-main");
});

chose5.addEventListener('click', function () {
  removeMenuMain();
  toi.classList.add("menu-main");
});

chose6.addEventListener('click', function () {
  removeMenuMain();
  lau.classList.add("menu-main");
});

chose7.addEventListener('click', function () {
  removeMenuMain();
  nuong.classList.add("menu-main");
});

function removeMenuMain() {
  let menuMain = document.querySelector('.menu-main');
  menuMain.classList.remove("menu-main");
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