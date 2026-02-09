/**
 * @file
 * Tabs component JavaScript
 */

(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.canvasBuilderTabs = {
    attach: function (context) {
      once('cb-tabs', '[data-cb-tabs]', context).forEach(function (tabsContainer) {
        const tabs = tabsContainer.querySelectorAll('[role="tab"]');
        const panels = tabsContainer.querySelectorAll('[role="tabpanel"]');

        // Click handler
        tabs.forEach(function (tab) {
          tab.addEventListener('click', function () {
            activateTab(tab);
          });

          // Keyboard navigation
          tab.addEventListener('keydown', function (e) {
            const currentIndex = parseInt(tab.dataset.tabIndex);
            let newIndex;

            switch (e.key) {
              case 'ArrowLeft':
                e.preventDefault();
                newIndex = currentIndex - 1;
                if (newIndex < 0) newIndex = tabs.length - 1;
                tabs[newIndex].focus();
                activateTab(tabs[newIndex]);
                break;
              case 'ArrowRight':
                e.preventDefault();
                newIndex = currentIndex + 1;
                if (newIndex >= tabs.length) newIndex = 0;
                tabs[newIndex].focus();
                activateTab(tabs[newIndex]);
                break;
              case 'Home':
                e.preventDefault();
                tabs[0].focus();
                activateTab(tabs[0]);
                break;
              case 'End':
                e.preventDefault();
                tabs[tabs.length - 1].focus();
                activateTab(tabs[tabs.length - 1]);
                break;
            }
          });
        });

        function activateTab(selectedTab) {
          const selectedIndex = parseInt(selectedTab.dataset.tabIndex);

          // Deactivate all tabs
          tabs.forEach(function (tab) {
            tab.setAttribute('aria-selected', 'false');
            tab.setAttribute('data-active', 'false');
            tab.setAttribute('tabindex', '-1');
          });

          // Hide all panels
          panels.forEach(function (panel) {
            panel.classList.add('hidden');
          });

          // Activate selected tab
          selectedTab.setAttribute('aria-selected', 'true');
          selectedTab.setAttribute('data-active', 'true');
          selectedTab.setAttribute('tabindex', '0');

          // Show selected panel
          panels[selectedIndex].classList.remove('hidden');
        }
      });
    }
  };

})(Drupal, once);
