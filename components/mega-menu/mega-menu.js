/**
 * @file
 * Mega Menu component JavaScript
 */

(function (Drupal) {
  'use strict';

  Drupal.behaviors.catsMegaMenu = {
    attach: function (context, settings) {
      const menus = context.querySelectorAll('.cats-megamenu');
      
      menus.forEach(function (menu) {
        if (menu.dataset.megaMenuInitialized) return;
        menu.dataset.megaMenuInitialized = 'true';
        
        const toggle = menu.querySelector('.cats-megamenu__toggle');
        const nav = menu.querySelector('.cats-megamenu__nav');
        const items = menu.querySelectorAll('.cats-megamenu-item--has-submenu');
        const isSticky = menu.classList.contains('cats-megamenu--sticky');
        const isTransparent = menu.classList.contains('cats-megamenu--transparent');
        
        // Mobile toggle
        if (toggle && nav) {
          toggle.addEventListener('click', function () {
            const isOpen = nav.classList.toggle('is-mobile-open');
            toggle.setAttribute('aria-expanded', isOpen);
            
            // Toggle icon
            const openIcon = toggle.querySelector('.cats-megamenu__toggle-open');
            const closeIcon = toggle.querySelector('.cats-megamenu__toggle-close');
            if (openIcon && closeIcon) {
              openIcon.classList.toggle('hidden', isOpen);
              closeIcon.classList.toggle('hidden', !isOpen);
            }
          });
        }
        
        // Submenu handling
        items.forEach(function (item) {
          const link = item.querySelector('.cats-megamenu-item__link');
          const submenu = item.querySelector('.cats-megamenu-item__submenu');
          const arrow = item.querySelector('.cats-megamenu-item__arrow');
          
          if (!link || !submenu) return;
          
          // Desktop: hover behavior
          let hoverTimeout;
          
          item.addEventListener('mouseenter', function () {
            clearTimeout(hoverTimeout);
            closeOtherSubmenus(item);
            item.classList.add('is-open');
            link.setAttribute('aria-expanded', 'true');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
          });
          
          item.addEventListener('mouseleave', function () {
            hoverTimeout = setTimeout(function () {
              item.classList.remove('is-open');
              link.setAttribute('aria-expanded', 'false');
              if (arrow) arrow.style.transform = '';
            }, 150);
          });
          
          // Mobile/Touch: click behavior
          link.addEventListener('click', function (e) {
            if (window.innerWidth < 1024 || 'ontouchstart' in window) {
              e.preventDefault();
              const isOpen = item.classList.toggle('is-open');
              link.setAttribute('aria-expanded', isOpen);
              if (arrow) arrow.style.transform = isOpen ? 'rotate(180deg)' : '';
              
              if (isOpen) {
                closeOtherSubmenus(item);
              }
            }
          });
        });
        
        function closeOtherSubmenus(currentItem) {
          items.forEach(function (item) {
            if (item !== currentItem) {
              item.classList.remove('is-open');
              const link = item.querySelector('.cats-megamenu-item__link');
              const arrow = item.querySelector('.cats-megamenu-item__arrow');
              if (link) link.setAttribute('aria-expanded', 'false');
              if (arrow) arrow.style.transform = '';
            }
          });
        }
        
        // Sticky/Transparent scroll handling
        if (isSticky || isTransparent) {
          let lastScrollY = 0;
          
          window.addEventListener('scroll', function () {
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > 50) {
              menu.classList.add('is-scrolled');
            } else {
              menu.classList.remove('is-scrolled');
            }
            
            lastScrollY = currentScrollY;
          }, { passive: true });
        }
        
        // Close menu on escape
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') {
            items.forEach(function (item) {
              item.classList.remove('is-open');
              const link = item.querySelector('.cats-megamenu-item__link');
              const arrow = item.querySelector('.cats-megamenu-item__arrow');
              if (link) link.setAttribute('aria-expanded', 'false');
              if (arrow) arrow.style.transform = '';
            });
            
            if (nav) {
              nav.classList.remove('is-mobile-open');
              if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
                const openIcon = toggle.querySelector('.cats-megamenu__toggle-open');
                const closeIcon = toggle.querySelector('.cats-megamenu__toggle-close');
                if (openIcon) openIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
              }
            }
          }
        });
        
        // Close mobile menu on resize
        window.addEventListener('resize', function () {
          if (window.innerWidth >= 1024 && nav) {
            nav.classList.remove('is-mobile-open');
            if (toggle) {
              toggle.setAttribute('aria-expanded', 'false');
              const openIcon = toggle.querySelector('.cats-megamenu__toggle-open');
              const closeIcon = toggle.querySelector('.cats-megamenu__toggle-close');
              if (openIcon) openIcon.classList.remove('hidden');
              if (closeIcon) closeIcon.classList.add('hidden');
            }
          }
        });
      });
    }
  };

})(Drupal);
