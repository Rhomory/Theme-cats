/**
 * @file
 * Carousel component JavaScript
 */

(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.canvasBuilderCarousel = {
    attach: function (context) {
      once('cb-carousel', '[data-cb-carousel]', context).forEach(function (carousel) {
        const track = carousel.querySelector('[data-carousel-track]');
        const slides = carousel.querySelectorAll('[data-slide]');
        const prevBtn = carousel.querySelector('[data-carousel-prev]');
        const nextBtn = carousel.querySelector('[data-carousel-next]');
        const dots = carousel.querySelectorAll('[data-carousel-dot]');

        const autoplay = carousel.dataset.autoplay === 'true';
        const speed = parseInt(carousel.dataset.speed) || 5000;
        const loop = carousel.dataset.loop === 'true';
        const effect = carousel.dataset.effect || 'slide';

        let currentSlide = 0;
        let autoplayInterval = null;
        let isTransitioning = false;

        // Initialize
        function init() {
          if (slides.length <= 1) return;

          updateSlide(0);

          if (autoplay) {
            startAutoplay();
          }

          // Pause autoplay on hover
          carousel.addEventListener('mouseenter', stopAutoplay);
          carousel.addEventListener('mouseleave', function () {
            if (autoplay) startAutoplay();
          });

          // Touch support
          let touchStartX = 0;
          let touchEndX = 0;

          carousel.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
          }, { passive: true });

          carousel.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
          }, { passive: true });

          function handleSwipe() {
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
              if (diff > 0) {
                nextSlide();
              } else {
                prevSlide();
              }
            }
          }
        }

        function updateSlide(index) {
          if (isTransitioning) return;
          isTransitioning = true;

          // Handle loop
          if (loop) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
          } else {
            if (index < 0) index = 0;
            if (index >= slides.length) index = slides.length - 1;
          }

          currentSlide = index;

          if (effect === 'slide') {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
          } else if (effect === 'fade') {
            slides.forEach(function (slide, i) {
              slide.style.opacity = i === currentSlide ? '1' : '0';
              slide.style.position = i === currentSlide ? 'relative' : 'absolute';
            });
          }

          // Update dots
          dots.forEach(function (dot, i) {
            const isActive = i === currentSlide;
            dot.classList.toggle('bg-white', isActive);
            dot.classList.toggle('bg-white/50', !isActive);
            dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
          });

          // Update buttons state
          if (!loop) {
            if (prevBtn) prevBtn.disabled = currentSlide === 0;
            if (nextBtn) nextBtn.disabled = currentSlide === slides.length - 1;
          }

          setTimeout(function () {
            isTransitioning = false;
          }, 500);
        }

        function nextSlide() {
          updateSlide(currentSlide + 1);
        }

        function prevSlide() {
          updateSlide(currentSlide - 1);
        }

        function goToSlide(index) {
          updateSlide(index);
        }

        function startAutoplay() {
          stopAutoplay();
          autoplayInterval = setInterval(nextSlide, speed);
        }

        function stopAutoplay() {
          if (autoplayInterval) {
            clearInterval(autoplayInterval);
            autoplayInterval = null;
          }
        }

        // Event listeners
        if (prevBtn) {
          prevBtn.addEventListener('click', function () {
            prevSlide();
            if (autoplay) {
              stopAutoplay();
              startAutoplay();
            }
          });
        }

        if (nextBtn) {
          nextBtn.addEventListener('click', function () {
            nextSlide();
            if (autoplay) {
              stopAutoplay();
              startAutoplay();
            }
          });
        }

        dots.forEach(function (dot) {
          dot.addEventListener('click', function () {
            const index = parseInt(dot.dataset.carouselDot);
            goToSlide(index);
            if (autoplay) {
              stopAutoplay();
              startAutoplay();
            }
          });
        });

        // Keyboard navigation
        carousel.addEventListener('keydown', function (e) {
          if (e.key === 'ArrowLeft') {
            prevSlide();
          } else if (e.key === 'ArrowRight') {
            nextSlide();
          }
        });

        init();
      });
    }
  };

})(Drupal, once);
