const images = document.querySelectorAll(".carousel-images img");
const container = document.querySelector(".carousel-images");
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");
const dotsContainer = document.querySelector(".carousel-dots");

let index = 0;

images.forEach((_, i) => {
    const dot = document.createElement("span");
    if (i === 0) dot.classList.add("active");
    dotsContainer.appendChild(dot);

    dot.addEventListener("click", () => {
        index = i;
        updateCarousel();
        resetAutoPlay();
    });
});

prevBtn.addEventListener("click", () => {
    index = (index - 1 + images.length) % images.length;
    updateCarousel();
    resetAutoPlay();
});

nextBtn.addEventListener("click", () => {
    index = (index + 1) % images.length;
    updateCarousel();
    resetAutoPlay();
});

function updateCarousel() {
    const width = images[0].clientWidth;
    container.style.transform = `translateX(${-index * width}px)`;

    document
        .querySelectorAll(".carousel-dots span")
        .forEach((dot, i) => dot.classList.toggle("active", i === index));
}

let autoPlay = setInterval(() => {
    nextBtn.click();
}, 3000);

const carousel = document.querySelector(".carousel");

carousel.addEventListener("mouseover", () => {
    clearInterval(autoPlay);
});

carousel.addEventListener("mouseleave", () => {
    autoPlay = setInterval(() => nextBtn.click(), 3000);
});

function resetAutoPlay() {
    clearInterval(autoPlay);
    autoPlay = setInterval(() => nextBtn.click(), 3000);
}
