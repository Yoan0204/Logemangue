document.addEventListener("DOMContentLoaded", () => {
  const images = document.querySelectorAll(".carousel-images img");
  const container = document.querySelector(".carousel-images");
  const prevBtn = document.querySelector(".prev");
  const nextBtn = document.querySelector(".next");
  const dotsContainer = document.querySelector(".carousel-dots");
  const carousel = document.querySelector(".carousel");

  // Vérifications basiques : si un élément manque, on logge et on sort proprement
  if (
    !container ||
    !images.length ||
    !dotsContainer ||
    !prevBtn ||
    !nextBtn ||
    !carousel
  ) {
    console.error(
      "Carousel: élément manquant. Vérifie que les classes .carousel, .carousel-images, .prev, .next et .carousel-dots existent dans ton HTML.",
    );
    return;
  }

  let index = 0;
  let autoPlay = null;
  let imageWidth = 0;

  // Attendre que toutes les images soient chargées pour récupérer la largeur correcte
  const waitForImages = () => {
    const promises = Array.from(images).map((img) => {
      if (img.complete) return Promise.resolve();
      return new Promise((resolve) =>
        img.addEventListener("load", resolve, { once: true }),
      );
    });
    return Promise.all(promises);
  };

  function calculateWidth() {
    // Au moins une image existe ; on prend sa largeur réelle
    imageWidth = images[0].clientWidth || container.clientWidth;
  }

  function updateCarousel() {
    calculateWidth();
    container.style.transform = `translateX(${-index * imageWidth}px)`;

    // Mise à jour des dots
    document.querySelectorAll(".carousel-dots span").forEach((dot, i) => {
      dot.classList.toggle("active", i === index);
    });
  }

  function startAutoPlay() {
    stopAutoPlay();
    autoPlay = setInterval(() => nextBtn.click(), 3000);
  }

  function stopAutoPlay() {
    if (autoPlay) {
      clearInterval(autoPlay);
      autoPlay = null;
    }
  }

  function resetAutoPlay() {
    stopAutoPlay();
    startAutoPlay();
  }

  // Création des dots
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

  // Boutons prev/next
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

  // Pause au survol
  carousel.addEventListener("mouseover", stopAutoPlay);
  carousel.addEventListener("mouseleave", startAutoPlay);

  // Recalculer si la fenêtre change de taille
  window.addEventListener("resize", () => {
    // on recalcule et on repositionne
    updateCarousel();
  });

  // Démarrer après chargement complet des images
  waitForImages()
    .then(() => {
      calculateWidth();
      updateCarousel();
      startAutoPlay();
    })
    .catch((err) => {
      // En cas d'erreur (rare), on tente quand même de démarrer
      console.warn("Carousel: erreur lors du chargement d'images", err);
      calculateWidth();
      updateCarousel();
      startAutoPlay();
    });
});
