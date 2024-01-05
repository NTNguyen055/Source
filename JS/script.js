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

  // document.getElementById('about').addEventListener ("click", function(event) {
  //   event.preventDefault();
  //   window.scrollTo({ top: 0, behavior: "smooth"});
  // });


  var book_table = new Swiper(".book-table-img-slider", {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    speed: 2000,
    effect: "coverflow",
    coverflowEffect: {
      rotate: 3,
      stretch: 2,
      depth: 100,
      modifier: 5,
      slideShadows: false,
    },
    loopAdditionSlides: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });

  var team_slider = new Swiper(".team-slider", {
    slidesPerView: 3,
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
        slidesPerView: 3,
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

  //activating tab of filter
  // let targets = document.querySelectorAll(".filter");
  // let activeTab = 0;
  // let old = 0;
  // let dur = 0.4;
  // let animation;

  // for (let i = 0; i < targets.length; i++) {
  //   targets[i].index = i;
  //   targets[i].addEventListener("click", moveBar);
  // }

  // // initial position on first === All 
  // gsap.set(".filter-active", {
  //   x: targets[0].offsetLeft,
  //   width: targets[0].offsetWidth
  // });

  // function moveBar() {
  //   if (this.index != activeTab) {
  //     if (animation && animation.isActive()) {
  //       animation.progress(1);
  //     }
  //     animation = gsap.timeline({
  //       defaults: {
  //         duration: 0.4
  //       }
  //     });
  //     old = activeTab;
  //     activeTab = this.index;
  //     animation.to(".filter-active", {
  //       x: targets[activeTab].offsetLeft,
  //       width: targets[activeTab].offsetWidth
  //     });

  //     animation.to(targets[old], {
  //       color: "#0d0d25",
  //       ease: "none"
  //     }, 0);
  //     animation.to(targets[activeTab], {
  //       color: "#fff",
  //       ease: "none"
  //     }, 0);

  //   }

  // }
});

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

        url: "searchContent.php",
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

