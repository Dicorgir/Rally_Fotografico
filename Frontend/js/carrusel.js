/**
 * Contenedor de las diapositivas del carrusel.
 * @type {HTMLElement}
 */
const track = document.querySelector('.carousel-track');

/**
 * Array de todas las diapositivas.
 * @type {HTMLElement[]}
 */
const slides = Array.from(track.children);

/**
 * Botón para ir a la diapositiva anterior.
 * @type {HTMLElement}
 */
const prevButton = document.querySelector('.carousel-button.prev');

/**
 * Botón para ir a la siguiente diapositiva.
 * @type {HTMLElement}
 */
const nextButton = document.querySelector('.carousel-button.next');

/**
 * Indicadores de posición del carrusel.
 * @type {HTMLElement[]}
 */
const indicators = Array.from(document.querySelectorAll('.carousel-indicator'));

/**
 * Índice de la diapositiva actual.
 * @type {number}
 */
let currentIndex = 0;

/**
 * Actualiza la posición del carrusel y los indicadores activos.
 * @function
 */
function updateCarousel() {
    const slideWidth = slides[0].getBoundingClientRect().width;
    track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentIndex);
    });
}

/**
 * Avanza a la siguiente diapositiva.
 * @event click
 */
nextButton.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % slides.length;
    updateCarousel();
});

/**
 * Retrocede a la diapositiva anterior.
 * @event click
 */
prevButton.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateCarousel();
});

/**
 * Permite seleccionar una diapositiva haciendo clic en un indicador.
 * @event click
 */
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        currentIndex = index;
        updateCarousel();
    });
});

/**
 * Actualiza el carrusel si cambia el tamaño de la ventana.
 * @event resize
 */
window.addEventListener('resize', updateCarousel);