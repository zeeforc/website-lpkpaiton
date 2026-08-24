document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-slide");
    if (slides.length === 0) return;

    let index = 0;
    const DISPLAY_TIME = 5000; // 10 detik
    let isPaused = false;

    function showNextSlide() {
        slides[index].classList.remove("active");

        index = (index + 1) % slides.length;

        slides[index].classList.add("active");
    }

    function startLoop() {
        setInterval(() => {
            if (!isPaused) {
                showNextSlide();
            }
        }, DISPLAY_TIME);
    }

    setTimeout(startLoop, DISPLAY_TIME);

    // Pause saat hover
    const slider = document.querySelector(".hero-slider");

    slider.addEventListener("mouseenter", () => {
        isPaused = true;
    });

    slider.addEventListener("mouseleave", () => {
        isPaused = false;
    });

    // Pause saat tab tidak aktif
    document.addEventListener("visibilitychange", () => {
        isPaused = document.hidden;
    });
});

// marquee running date
document.addEventListener("DOMContentLoaded", function () {
    const dateElement = document.getElementById("runningDate");

    function updateDate() {
        const now = new Date();

        const options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };

        const formattedDate = now.toLocaleDateString("id-ID", options);

        dateElement.textContent = formattedDate;
    }

    updateDate();
});

// scroll more news button
const newsScrollContainer = document.getElementById("newsListScroll");
const newsScrollDownBtn = document.getElementById("newsScrollDown");

if (newsScrollContainer && newsScrollDownBtn) {
    newsScrollDownBtn.addEventListener("click", () => {
        newsScrollContainer.scrollBy({
            top: 260,
            behavior: "smooth",
        });
    });
}

// document.addEventListener("DOMContentLoaded", function () {
//     const popup = document.getElementById("newsPopupHighlight");
//     const closeBtn = document.getElementById("closePopupBtn");
//     let popupInterval;
//     let hideTimeout;

//     if (popup && closeBtn) {
//         // Fungsi untuk menjalankan satu siklus popup
//         const runPopupCycle = () => {
//             // 1. Tampilkan Popup
//             popup.classList.add("show");

//             // 2. Sembunyikan setelah 20 detik (20000 ms)
//             hideTimeout = setTimeout(() => {
//                 popup.classList.remove("show");
//             }, 20000);
//         };

//         // Tunggu 2 detik saat pertama kali halaman di load, lalu mulai siklusnya
//         setTimeout(() => {
//             runPopupCycle(); // Jalankan siklus pertama

//             // Ulangi siklus ini setiap 24 detik
//             // (20 detik waktu tampil + 4 detik waktu sembunyi = 24 detik total siklus)
//             popupInterval = setInterval(runPopupCycle, 24000);
//         }, 1000);

//         // Menghentikan popup sepenuhnya jika user mengklik tombol tutup (X)
//         closeBtn.addEventListener("click", function (e) {
//             e.preventDefault();
//             popup.classList.remove("show");

//             // Hapus timer agar tidak muncul lagi
//             clearInterval(popupInterval);
//             clearTimeout(hideTimeout);
//         });
//     }
// });
