/**
 * @file
 * Counter component JavaScript
 */

(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.catsCounterAction = {
    attach: function (context) {
      console.log('Cats Counter: Iniciando script...');
      
      once('cats-counter-init', '[data-cb-counter]', context).forEach(function (counter) {
        console.log('Cats Counter: Elemento encontrado:', counter);
        
        const valueElement = counter.querySelector('[data-counter-value]');
        if (!valueElement) return;

        const target = parseFloat(counter.getAttribute('data-target')) || 0;
        const duration = parseInt(counter.getAttribute('data-duration')) || 2000;

        let hasAnimated = false;

        function animateCounter() {
          if (hasAnimated) return;
          hasAnimated = true;
          
          const startTime = performance.now();
          const startValue = 0;

          function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
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

        // Observer muy sensible
        const observer = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              animateCounter();
            }
          });
        }, { threshold: 0.1 });

        observer.observe(counter);

        // Disparo forzado para el editor
        setTimeout(animateCounter, 300);
      });
    }
  };

})(Drupal, once);
