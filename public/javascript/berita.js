document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-slide");
    if (slides.length === 0) return;

    let index = 0;
    const DISPLAY_TIME = 5000;
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

    const slider = document.querySelector(".hero-slider");

    slider.addEventListener("mouseenter", () => {
        isPaused = true;
    });

    slider.addEventListener("mouseleave", () => {
        isPaused = false;
    });

    document.addEventListener("visibilitychange", () => {
        isPaused = document.hidden;
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const dateElements = document.querySelectorAll(".runningDate");
    if (dateElements.length === 0) return;

    function updateDate() {
        const now = new Date();

        const options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };

        const formattedDate = now.toLocaleDateString("id-ID", options);

        dateElements.forEach(el => {
            el.textContent = formattedDate;
        });
    }

    updateDate();
});

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