(function ($) {
    "use strict";

    const proBanner = document.querySelector("#proBanner");
    const bannerClose = document.querySelector("#bannerClose");

    // Jika elemen tidak ada, hentikan script (jangan bikin JS lain mati)
    if (!proBanner || !bannerClose) {
        return;
    }

    if ($.cookie("corona-pro-banner") !== "true") {
        proBanner.classList.add("d-flex");
        proBanner.classList.remove("d-none");
    } else {
        proBanner.classList.add("d-none");
        proBanner.classList.remove("d-flex");
    }

    bannerClose.addEventListener("click", function () {
        proBanner.classList.add("d-none");
        proBanner.classList.remove("d-flex");

        const date = new Date();
        date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
        $.cookie("corona-pro-banner", "true", { expires: date });
    });
})(jQuery);
