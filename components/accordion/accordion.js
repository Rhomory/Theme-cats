/**
 * @file
 * Accordion component JavaScript
 */

(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.catsAccordion = {
    attach: function (context) {
      once('cats-accordion', '[data-cats-accordion]', context).forEach(function (accordion) {
        const allowMultiple = accordion.dataset.allowMultiple === 'true';
        const items = accordion.querySelectorAll('[data-accordion-item]');

        items.forEach(function (item) {
          const trigger = item.querySelector('[data-accordion-trigger]');
          const content = item.querySelector('[data-accordion-content]');
          const icon = item.querySelector('[data-accordion-icon]');

          if (!trigger || !content) return;

          trigger.addEventListener('click', function (e) {
            e.preventDefault();
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';

            if (!allowMultiple && !isOpen) {
              // Close other items
              items.forEach(function (otherItem) {
                if (otherItem !== item) {
                  const otherTrigger = otherItem.querySelector('[data-accordion-trigger]');
                  const otherContent = otherItem.querySelector('[data-accordion-content]');
                  const otherIcon = otherItem.querySelector('[data-accordion-icon]');
                  
                  if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
                  if (otherContent) {
                    otherContent.style.maxHeight = '0';
                    otherContent.style.opacity = '0';
                  }
                  if (otherIcon) otherIcon.classList.remove('rotate-180');
                }
              });
            }

            // Toggle current item
            const newStatus = !isOpen;
            trigger.setAttribute('aria-expanded', newStatus ? 'true' : 'false');
            
            if (newStatus) {
              content.style.maxHeight = content.scrollHeight + 'px';
              content.style.opacity = '1';
              if (icon) icon.classList.add('rotate-180');
            } else {
              content.style.maxHeight = '0';
              content.style.opacity = '0';
              if (icon) icon.classList.remove('rotate-180');
            }
          });
        });
      });
    }
  };

})(Drupal, once);
