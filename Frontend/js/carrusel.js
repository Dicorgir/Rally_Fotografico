const track = document.querySelector('.carousel-track'); // Contenedor de las diapositivas
const slides = Array.from(track.children); // Array de todas las diapositivas
const prevButton = document.querySelector('.carousel-button.prev'); // Botón anterior
const nextButton = document.querySelector('.carousel-button.next'); // Botón siguiente
const indicators = Array.from(document.querySelectorAll('.carousel-indicator')); // Indicadores de posición
let currentIndex = 0; // Índice de la diapositiva actual

// Actualiza la posición del carrusel y los indicadores
function updateCarousel() {
    const slideWidth = slides[0].getBoundingClientRect().width; // Ancho de una diapositiva
    track.style.transform = `translateX(-${currentIndex * slideWidth}px)`; // Mueve el track
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentIndex); // Marca el indicador activo
    });
}

// Avanza a la siguiente diapositiva
nextButton.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % slides.length;
    updateCarousel();
});

// Retrocede a la diapositiva anterior
prevButton.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateCarousel();
});

// Permite seleccionar una diapositiva haciendo clic en un indicador
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        currentIndex = index;
        updateCarousel();
    });
});

// Actualiza el carrusel si cambia el tamaño de la ventana
window.addEventListener('resize', updateCarousel);