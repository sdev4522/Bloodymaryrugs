// abcdfsgh
(function () {
  "use strict";

  console.log("🔥 product-swiper.js EXECUTING");

  function initSwiper() {
    const swiperView = document.querySelector(".swiper-view");
    const swiperPreview = document.querySelector(".swiper-preview");

    if (!swiperView || !swiperPreview) {
      return false;
    }

    if (swiperPreview.classList.contains("swiper-initialized")) {
      return true;
    }

    const thumbs = new Swiper(swiperView, {
      direction: "horizontal",
      slidesPerView: 4,
      spaceBetween: 10,
      watchSlidesProgress: true,
    });

    new Swiper(swiperPreview, {
      direction: "horizontal",
      slidesPerView: 1,
      spaceBetween: 10,
      navigation: {
        nextEl: swiperPreview.querySelector(".swiper-button-next"),
        prevEl: swiperPreview.querySelector(".swiper-button-prev"),
      },
      thumbs: { swiper: thumbs },
      loop: false,
    });

    console.log("✅ Swiper initialized");
    return true;
  }

  // Try immediately
  if (initSwiper()) return;

  // Watch DOM until WOWY injects product gallery
  const observer = new MutationObserver(() => {
    if (initSwiper()) {
      observer.disconnect();
    }
  });

  observer.observe(document.body, {
    childList: true,
    subtree: true,
  });

})();
