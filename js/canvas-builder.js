/**
 * @file
 * Canvas Builder Theme - JavaScript global
 */

(function (Drupal, once) {
  'use strict';

  /**
   * Canvas Builder namespace
   */
  Drupal.canvasBuilder = Drupal.canvasBuilder || {};

  /**
   * Utilidades generales
   */
  Drupal.canvasBuilder.utils = {
    /**
     * Verifica si un elemento está visible en el viewport
     */
    isInViewport: function (element, threshold = 0.5) {
      const rect = element.getBoundingClientRect();
      const windowHeight = window.innerHeight || document.documentElement.clientHeight;
      return (
        rect.top <= windowHeight * (1 - threshold) &&
        rect.bottom >= windowHeight * threshold
      );
    },

    /**
     * Debounce function
     */
    debounce: function (func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    },

    /**
     * Throttle function
     */
    throttle: function (func, limit) {
      let inThrottle;
      return function executedFunction(...args) {
        if (!inThrottle) {
          func(...args);
          inThrottle = true;
          setTimeout(() => (inThrottle = false), limit);
        }
      };
    }
  };

  /**
   * Comportamiento global para animaciones al scroll
   */
  Drupal.behaviors.canvasBuilderAnimations = {
    attach: function (context, settings) {
      once('cb-animate', '[data-cb-animate]', context).forEach(function (element) {
        const animation = element.dataset.cbAnimate;
        const delay = element.dataset.cbAnimateDelay || 0;

        const observer = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                setTimeout(function () {
                  element.classList.add('cb-animate-' + animation);
                  element.classList.add('cb-animated');
                }, delay);
                observer.unobserve(element);
              }
            });
          },
          { threshold: 0.2 }
        );

        observer.observe(element);
      });
    }
  };

})(Drupal, once);
