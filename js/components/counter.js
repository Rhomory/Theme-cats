/**
 * @file
 * Counter component JavaScript
 */

(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.catsCounterAction = {
    attach: function (context) {
      console.log('Cats Counter: Behavior attached');
      
      once('cats-counter-init', '[data-cb-counter]', context).forEach(function (counter) {
        console.log('Cats Counter: Element found', counter);
        
        const valueElement = counter.querySelector('[data-counter-value]');
        if (!valueElement) {
          console.error('Cats Counter: Value element NOT found inside', counter);
          return;
        }

        const target = parseFloat(counter.getAttribute('data-target')) || 0;
        const duration = parseInt(counter.getAttribute('data-duration')) || 2000;
        
        console.log('Cats Counter: Target is', target, 'Duration is', duration);

        let hasAnimated = false;

        function animateCounter() {
          if (hasAnimated) return;
          hasAnimated = true;
          console.log('Cats Counter: Animation starting for', target);

          const startTime = performance.now();
          const startValue = 0;

          function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing simple (ease out)
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.floor(startValue + (target - startValue) * easeOut);
            
            valueElement.textContent = currentValue.toLocaleString();

            if (progress < 1) {
              requestAnimationFrame(updateCounter);
            } else {
              valueElement.textContent = target.toLocaleString();
              console.log('Cats Counter: Animation finished');
            }
          }

          requestAnimationFrame(updateCounter);
        }

        // Trigger on visibility
        try {
          const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                animateCounter();
                observer.unobserve(counter);
              }
            });
          }, { threshold: 0.01 });
          observer.observe(counter);
        } catch (e) {
          console.error('Cats Counter: IntersectionObserver failed, using fallback', e);
          animateCounter();
        }

        // Emergency delay trigger for editors
        setTimeout(function() {
          if (!hasAnimated) {
            console.log('Cats Counter: Emergency trigger fired');
            animateCounter();
          }
        }, 800);
      });
    }
  };

})(Drupal, once);
