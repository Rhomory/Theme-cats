/**
 * @file
 * Video component JavaScript
 */

(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.catsVideo = {
    attach: function (context) {
      once('cats-video', '[data-cb-video]', context).forEach(function (videoWrapper) {
        const poster = videoWrapper.querySelector('[data-video-poster]');
        const container = videoWrapper.querySelector('[data-video-container]');
        const lazy = videoWrapper.dataset.lazy === 'true';

        if (lazy && poster && container) {
          poster.addEventListener('click', function () {
            // Hide poster
            poster.style.display = 'none';
            
            // Show video container
            container.classList.remove('hidden');
            container.style.display = 'block';

            // If it's an iframe, trigger autoplay
            const iframe = container.querySelector('iframe');
            if (iframe) {
              let src = iframe.src;
              if (!src.includes('autoplay=1')) {
                src += (src.includes('?') ? '&' : '?') + 'autoplay=1';
                iframe.src = src;
              }
            }

            // If it's a local video, play it
            const video = container.querySelector('video');
            if (video) {
              video.play();
            }
          });
        }
      });
    }
  };

})(Drupal, once);
