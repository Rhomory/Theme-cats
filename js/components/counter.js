/**
 * @file
 * Counter component JavaScript
 */

(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.canvasBuilderCounter = {
    attach: function (context) {
      once('cb-counter', '[data-cb-counter]', context).forEach(function (counter) {
        const valueElement = counter.querySelector('[data-counter-value]');
        const target = parseInt(counter.dataset.target) || 100;
        const duration = parseInt(counter.dataset.duration) || 2000;

        let hasAnimated = false;

        function animateCounter() {
          if (hasAnimated) return;
          hasAnimated = true;

          const startTime = performance.now();
          const startValue = 0;

          function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function (ease-out)
            const easeOut = 1 - Math.pow(1 - progress, 3);

            const currentValue = Math.floor(startValue + (target - startValue) * easeOut);
            valueElement.textContent = currentValue.toLocaleString();

            if (progress < 1) {
              requestAnimationFrame(updateCounter);
            } else {
              valueElement.textContent = target.toLocaleString();
            }
          }

          requestAnimationFrame(updateCounter);
        }

        // Use Intersection Observer to trigger animation when visible
        const observer = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                animateCounter();
                observer.unobserve(counter);
              }
            });
          },
          { threshold: 0.5 }
        );

        observer.observe(counter);
      });
    }
  };

})(Drupal, once);
