// Slider galeri dengan tombol Prev dan Next
document.addEventListener("DOMContentLoaded", () => {
    const galleryItems = document.querySelectorAll(".gallery-item");
    const stack = document.getElementById("galleryStack");
    const overlay = document.getElementById("previewOverlay");
    const previewImg = document.getElementById("previewImage");
    const btnPrev = document.getElementById("galleryPrev");
    const btnNext = document.getElementById("galleryNext");

    if (!galleryItems.length || !stack) return;

    const total = galleryItems.length;
    let currentIndex = 0;

    function applyPositions(frontIndex) {
        galleryItems.forEach((item, idx) => {
            item.classList.remove("pos-front", "pos-left", "pos-right");

            if (idx === frontIndex) {
                item.classList.add("pos-front");
            } else if (idx === (frontIndex + 1) % total) {
                item.classList.add("pos-right");
            } else {
                item.classList.add("pos-left");
            }
        });
    }

    // posisi awal
    applyPositions(currentIndex);

    // tombol Next
    if (btnNext) {
        btnNext.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % total;
            applyPositions(currentIndex);
        });
    }

    // tombol Prev
    if (btnPrev) {
        btnPrev.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + total) % total;
            applyPositions(currentIndex);
        });
    }

    // klik gambar untuk preview
    galleryItems.forEach((item) => {
        item.addEventListener("click", () => {
            if (!overlay || !previewImg) return;
            previewImg.src = item.src;
            previewImg.alt = item.alt || "Preview";
            overlay.classList.add("show");
        });
    });

    // tutup preview
    if (overlay) {
        overlay.addEventListener("click", () => {
            overlay.classList.remove("show");
        });
    }

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && overlay) {
            overlay.classList.remove("show");
        }
    });
});
