document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll(".visi-misi-card .visi-tabs span")
        .forEach(function (tab) {
            tab.addEventListener("click", function () {
                const card = tab.closest(".visi-misi-card");
                const type = tab.dataset.tab; // 'visi' atau 'misi'

                // toggle class active di tab
                card.querySelectorAll(".visi-tabs span").forEach(function (t) {
                    t.classList.remove("active");
                });
                tab.classList.add("active");

                const visiText = card.querySelector(".visi-text");
                const misiText = card.querySelector(".misi-text");
                const titleEl = card.querySelector(".visi-title");

                if (type === "visi") {
                    visiText.classList.remove("d-none");
                    misiText.classList.add("d-none");

                    if (titleEl && titleEl.dataset.misiTitle) {
                        titleEl.textContent = titleEl.dataset.visiTitle;
                    }
                } else {
                    visiText.classList.add("d-none");
                    misiText.classList.remove("d-none");

                    if (titleEl && titleEl.dataset.misiTitle) {
                        titleEl.textContent = titleEl.dataset.misiTitle;
                    }
                }
            });
        });
});
