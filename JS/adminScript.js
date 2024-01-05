
// const navi = document.querySelectorAll(".navi ul li"),

//     main_1 = document.querySelector(".main-1"),
//     main_2 = document.querySelector(".main-2"),
//     main_3 = document.querySelector(".main-3"),
//     main_4 = document.querySelector(".main-4"),
//     main_5 = document.querySelector(".main-5"),
//     main_6 = document.querySelector(".main-6");

// navi.forEach (function (naviLi) {
//     naviLi.addEventListener("click", function() {
//         removeNavi();
//         naviLi.classList.add("nv");
//     });
// });

// function removeNavi() {
//     let nv = document.querySelector('.nv');
//     nv.classList.remove("nv");
// }


// const navi_1 = document.querySelector(".navi-1"),
//     navi_2 = document.querySelector(".navi-2"),
//     navi_3 = document.querySelector(".navi-3"),
//     navi_4 = document.querySelector(".navi-4"),
//     navi_5 = document.querySelector(".navi-5"),
//     navi_6 = document.querySelector(".navi-6");

// navi_1.addEventListener('click', function() {
//     removeMain();
//     main_1.classList.add("main-n");
// });

// navi_2.addEventListener('click', function() {
//     removeMain();
//     main_2.classList.add("main-n");
// });

// navi_3.addEventListener('click', function() {
//     removeMain();
//     main_3.classList.add("main-n");
// });

// navi_4.addEventListener('click', function() {
//     removeMain();
//     main_4.classList.add("main-n");
// });

// navi_5.addEventListener('click', function() {
//     removeMain();
//     main_5.classList.add("main-n");
// });

// navi_6.addEventListener('click', function() {
//     removeMain();
//     main_6.classList.add("main-n");
// });

// function removeMain() {
//     let mainn = document.querySelector('.main-n');
//     mainn.classList.remove("main-n");
// }


//toggle

let toggle = document.querySelector(".small-menu"),
    navigation = document.querySelector(".navi"),
    mainnn = document.querySelector(".main");

toggle.onclick = function() {
    navigation.classList.toggle("active");
    mainnn.classList.toggle("active");
}



const preloader = document.querySelector("[data-preaload]");

window.addEventListener("load", function () {
preloader.classList.add("loaded");
document.body.classList.add("loaded");
});


// Admin
jQuery(".user").click(function () {
    jQuery(".user-box").toggleClass("toggled");
});

jQuery(".user.toggled").click(function () {
    jQuery(".user-box").removeClass("toggled");
});