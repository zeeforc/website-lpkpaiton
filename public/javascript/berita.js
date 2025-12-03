// Scroll "Berita Lainnya" dengan tombol oranye
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
