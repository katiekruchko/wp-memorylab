
    /*
    mobile menu
    */
    document.addEventListener('DOMContentLoaded', function() {
      const burgerBtn = document.getElementById('burger-menu');
      const sidebar = document.querySelector('.mm-sidebar');
      const closeBtn = document.querySelector('.mm-close-btn');
      const overlay = document.querySelector('.mm-overlay');
      const body = document.body;
    
      // Универсальный обработчик кликов по документу
      document.addEventListener('click', function(e) {
        // Проверяем, был ли клик внутри кнопки меню
        if (burgerBtn && burgerBtn.contains(e.target)) {
          e.preventDefault(); // предотвращаем возможные действия по умолчанию
          sidebar.classList.add('open');
          overlay.classList.add('active');
          body.classList.add('no-scroll');
          return;
        }
    
        // Закрытие меню по клику на оверлей
        if (overlay && overlay.classList.contains('active') && e.target === overlay) {
          sidebar.classList.remove('open');
          overlay.classList.remove('active');
          body.classList.remove('no-scroll');
          return;
        }
    
        // Закрытие меню по клику на × (внутри кнопки)
        if (closeBtn && closeBtn.contains(e.target)) {
          sidebar.classList.remove('open');
          overlay.classList.remove('active');
          body.classList.remove('no-scroll');
          return;
        }
    
        // Закрытие меню при клике вне его (опционально)
        if (sidebar && sidebar.classList.contains('open')) {
          if (!sidebar.contains(e.target) && !burgerBtn.contains(e.target)) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            body.classList.remove('no-scroll');
          }
        }
      });
    });
        /*
        end mobile menu
        */
(function() {
  'use strict';
  
  function addActiveClass(element) {
    if (!element) return;
    element.classList.add('menu-link-active');
    console.log('Добавлен класс для:', element);
  }
  
  function removeActiveClasses() {
    document.querySelectorAll('.header-menu-list li.menu-link-active, .mm-nav-item.menu-link-active')
      .forEach(el => el.classList.remove('menu-link-active'));
  }
  
  function updateActiveMenu() {
    const currentPath = window.location.pathname;
    const currentHash = window.location.hash;
    
    removeActiveClasses();
    
    // Если есть якорь
    if (currentHash && currentHash.startsWith('#')) {
      const targetId = currentHash.replace('#', '');
      
      // Ищем в десктопном меню
      document.querySelectorAll('.header-menu-list li a').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes('#' + targetId)) {
          addActiveClass(link.closest('li'));
        }
      });
      
      // Ищем в мобильном меню (элементы сами являются ссылками)
      document.querySelectorAll('.mm-nav-item').forEach(item => {
        const href = item.getAttribute('href');
        if (href && href.includes('#' + targetId)) {
          addActiveClass(item);
        }
      });
    } 
    // Если нет якоря - подсвечиваем страницу
    else {
      // Нормализация пути
      const normalizePath = (path) => {
        if (!path) return '/';
        if (path.endsWith('/') && path !== '/') {
          path = path.slice(0, -1);
        }
        return path;
      };
      
      const currentNormalized = normalizePath(currentPath);
      
      // Десктопное меню
      document.querySelectorAll('.header-menu-list li a').forEach(link => {
        const href = link.getAttribute('href');
        if (!href || href.includes('#')) return;
        
        let linkPath = href;
        if (linkPath.startsWith(window.location.origin)) {
          linkPath = linkPath.replace(window.location.origin, '');
        }
        
        const linkNormalized = normalizePath(linkPath);
        
        if (linkNormalized === currentNormalized) {
          addActiveClass(link.closest('li'));
        }
      });
      
      // Мобильное меню
      document.querySelectorAll('.mm-nav-item').forEach(item => {
        const href = item.getAttribute('href');
        if (!href || href.includes('#')) return;
        
        let linkPath = href;
        if (linkPath.startsWith(window.location.origin)) {
          linkPath = linkPath.replace(window.location.origin, '');
        }
        
        const linkNormalized = normalizePath(linkPath);
        
        if (linkNormalized === currentNormalized) {
          addActiveClass(item);
        }
      });
    }
  }
  
  // Запускаем при загрузке
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(updateActiveMenu, 200);
  });
  
  window.addEventListener('load', () => {
    setTimeout(updateActiveMenu, 300);
  });
  
  // При клике на якорь
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (link && link.getAttribute('href') && link.getAttribute('href').includes('#')) {
      setTimeout(updateActiveMenu, 300);
    }
  });
  
  // При открытии мобильного меню
  document.addEventListener('click', (e) => {
    const burger = document.getElementById('burger-menu');
    if (burger && burger.contains(e.target)) {
      setTimeout(updateActiveMenu, 400);
    }
  });
  
  console.log('Менеджер меню запущен');
})();
/**/
/*
Заполнение формы
/**/
// Функция для открытия/закрытия выпадающих списков
function toggleDropdown(triggerId, dropdownId) {
  const trigger = document.getElementById(triggerId);
  const dropdown = document.getElementById(dropdownId);

  // Закрываем все другие выпадающие списки И убираем класс 'open' у их триггеров
  document.querySelectorAll(".ai-dropdown").forEach((d) => {
    if (d !== dropdown) {
      d.style.display = "none";
      // Находим соответствующий триггер по ID (например, "event-type-dropdown" → "event-type-trigger")
      const otherTriggerId = d.id.replace("-dropdown", "-trigger");
      const otherTrigger = document.getElementById(otherTriggerId);
      if (otherTrigger) {
        otherTrigger.classList.remove("open");
      }
    }
  });

  // Переключаем текущий
  if (dropdown.style.display === "block") {
    dropdown.style.display = "none";
    trigger.classList.remove("open");
  } else {
    dropdown.style.display = "block";
    trigger.classList.add("open");
  }
}

// Закрытие при клике вне выпадающего списка
document.addEventListener("click", function (event) {
  if (!event.target.closest(".ai-select-wrapper")) {
    document.querySelectorAll(".ai-dropdown").forEach((d) => {
      d.style.display = "none";
    });
    document.querySelectorAll(".ai-custom-select").forEach((s) => {
      s.classList.remove("open");
    });
  }
});

// Инициализация выпадающих списков
function safeAddClickListener(triggerId, dropdownId) {
  const trigger = document.getElementById(triggerId);
  if (trigger) {
    trigger.addEventListener("click", function () {
      toggleDropdown(triggerId, dropdownId);
    });
  }
}

// Используем безопасную функцию
safeAddClickListener("event-type-trigger", "event-type-dropdown");
safeAddClickListener("duration-trigger", "duration-dropdown");
safeAddClickListener("format-trigger", "format-dropdown");

// Обработка выбора в выпадающем списке
document.querySelectorAll(".ai-dropdown-item").forEach((item) => {
  item.addEventListener("click", function () {
    const wrapper = this.closest(".ai-select-wrapper");
    const select = wrapper.querySelector(".ai-custom-select");
    const hiddenInput = wrapper.querySelector("input[type='hidden']");
    const errorMsg = wrapper.querySelector(".ai-error-message");
    const label = this.textContent.trim();
    const dropdown = this.closest(".ai-dropdown");

    // Определяем значение
    let value = this.dataset.value !== undefined ? this.dataset.value : label;

    // Снимаем галочку со всех пунктов в этом списке
    dropdown.querySelectorAll(".ai-dropdown-item").forEach(el => {
      el.classList.remove("selected");
    });

    // Ставим галочку на выбранный
    this.classList.add("selected");

    // Обновляем отображение триггера
    select.querySelector("span").textContent = label;

    // Сохраняем в скрытый инпут
    if (hiddenInput) {
      hiddenInput.value = value;
    }

    // Скрываем ошибку
    if (errorMsg) {
      errorMsg.style.display = "none";
      wrapper.classList.remove("ai-select-wrapper-error");
    }

    // Закрываем выпадающий список
    dropdown.style.display = "none";
    select.classList.remove("open");
  });
});






// Валидация при нажатии на кнопку
document.addEventListener("DOMContentLoaded", () => {
  const submitBtn = document.getElementById("ai-submit-btn");
  if (submitBtn) {
    submitBtn.addEventListener("click", function (e) {
      e.preventDefault();

      let isValid = true;




// Проверяем ТОЛЬКО выпадающие списки (игнорируем слайдер)
document.querySelectorAll(".ai-select-wrapper").forEach((wrapper) => {
  const hiddenInput = wrapper.querySelector("input[type='hidden']");
  const errorMsg = wrapper.querySelector(".ai-error-message");

  if (!hiddenInput || !hiddenInput.value.trim()) {
    if (errorMsg) {
      errorMsg.style.display = "block";
      wrapper.classList.add("ai-select-wrapper-error");
    }
    isValid = false;
  } else {
    if (errorMsg) {
      errorMsg.style.display = "none";
      wrapper.classList.remove("ai-select-wrapper-error");
    }
    wrapper.classList.remove("ai-select-wrapper-error");
  }
});


      if (isValid) {
        console.log("Все селекты заполнены. Форма готова к отправке.");
        // Здесь можно отправить данные, например:
        // const formData = new FormData(document.querySelector('.ai-form'));
        // fetch('/submit', { method: 'POST', body: formData });
        document.getElementById("show-ai-results").style.display = "block";

      } else {
        console.log("Заполните все выпадающие списки.");
      }
    });
  }

  // Инициализация слайдера (без валидации)
  const slider = document.getElementById("people-slider");
  const valueDisplay = document.querySelector(".ai-slider-value");

  if (slider && valueDisplay) {
    const ratio = 100 / (+slider.max - +slider.min);
    slider.addEventListener("input", function () {
      valueDisplay.textContent = this.value;
      const value = ratio * (this.value - this.min);
      this.parentElement.style.setProperty("--value", value);
      this.style.setProperty("--progress", `${value}%`);
    });
    slider.dispatchEvent(new Event("input"));
  }
});

/* 
-------------------
Прокрутка изображений в блоке Инстаграм
-------------------
*/
document.addEventListener('DOMContentLoaded', () => {
  // Вспомогательная функция: ждём загрузки всех изображений в контейнере
  function waitForImages(container) {
    const images = container.querySelectorAll('img');
    return Promise.all(
      Array.from(images).map(img =>
        (img.complete && img.naturalHeight > 0)
          ? Promise.resolve()
          : new Promise(r => { img.onload = img.onerror = r; })
      )
    );
  }

  // Инициализация одного ряда
  function initRow(row, direction) {
    // Сохраняем оригинальные элементы ДО клонирования
    const originalItems = Array.from(row.querySelectorAll('.instagram-item'));
    if (originalItems.length === 0) return;

    // Ждём, пока все картинки подгрузятся → только потом клонируем и измеряем
    waitForImages(row).then(() => {
      // Удваиваем контент: добавляем копию оригинала в конец
      const fragment = document.createDocumentFragment();
      originalItems.forEach(item => {
        fragment.appendChild(item.cloneNode(true));
      });
      row.appendChild(fragment);

      // Теперь можно точно измерить ширину одного комплекта
      const singleWidth = originalItems.reduce((total, item) => {
        return total + item.getBoundingClientRect().width;
      }, 0);

      // Инициализируем позицию в зависимости от направления
      let pos = direction === 'right' ? -singleWidth : 0;

      function animate() {
        if (direction === 'left') {
          pos -= 0.35; // движемся влево → уменьшаем translateX
          if (pos <= -singleWidth) pos = 0;
        } else if (direction === 'right') {
          pos += 0.35; // движемся вправо → увеличиваем translateX
          if (pos >= 0) pos = -singleWidth;
        }

        row.style.transform = `translateX(${pos}px)`;
        requestAnimationFrame(animate);
      }

      animate();
    });
  }

  // Запускаем анимацию для обоих рядов
  const row1 = document.querySelector('.row-insta-1');
  const row2 = document.querySelector('.row-insta-2');

  if (row1) initRow(row1, 'right'); // → вправо
  if (row2) initRow(row2, 'left');  // ← влево
});
/* 
-------------------
Конец - Прокрутка изображений в блоке Инстаграм
-------------------
*/

/*
------------------
Скрыть header при скролле вниз и показать при скролле наверх
------------------
*/
let lastScrollY = window.scrollY;
const header = document.querySelector('.site-header');

window.addEventListener('scroll', () => {
  // Отключаем обработчик во время анимации прокрутки (например, при smooth scroll)
  if (window.scrollY > lastScrollY && window.scrollY > 50) {
    // Прокрутка вниз — скрываем
    header.classList.add('hidden');
  } else {
    // Прокрутка вверх — показываем
    header.classList.remove('hidden');
  }

  lastScrollY = window.scrollY;
});

/*
------------------
Конец - Скрыть header при скролле вниз и показать при скролле наверх
------------------
*/

/*
---------------------
попап qr-code
---------------------
*/
 (function() {
        // Ищем элементы. Класс триггера .qr-code (он не входит в нейминг попапа, это отдельный элемент на сайте)
        const qrTriggers = document.querySelectorAll('.qr-code');
        // Элементы попапа с уникальными id и префиксованными классами
        const modalOverlay = document.getElementById('qrModal');
        const closeButton = document.getElementById('qrClosePopupButton');

        // Функция открытия попапа
        function openModal() {
            if (modalOverlay) {
                modalOverlay.classList.add('active');
                // Небольшая блокировка скролла на body (для лучшего UX)
                document.body.style.overflow = 'hidden';
            }
        }

        // Функция закрытия попапа
        function closeModal() {
            if (modalOverlay) {
                modalOverlay.classList.remove('active');
                document.body.style.overflow = ''; // возвращаем скролл
            }
        }

        // Навешиваем событие на все элементы .qr-code
        if (qrTriggers.length > 0) {
            qrTriggers.forEach(trigger => {
                trigger.addEventListener('click', (event) => {
                    event.stopPropagation();
                    openModal();
                });
            });
        } else {
            console.warn('Элемент с классом "qr-code" не найден. Убедитесь, что разметка содержит .qr-code');
        }

        // Закрытие по кнопке "Закрыть"
        if (closeButton) {
            closeButton.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal();
            });
        }

        // Дополнительно: клик по оверлею (затемненной области) тоже закрывает попап (удобно)
        if (modalOverlay) {
            modalOverlay.addEventListener('click', (event) => {
                // Если клик был именно на оверлее, а не на внутренней popup-card, то закрываем
                if (event.target === modalOverlay) {
                    closeModal();
                }
            });

            // Закрытие по клавише Escape
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modalOverlay.classList.contains('active')) {
                    closeModal();
                }
            });
        }

        // Убеждаемся, что modalOverlay скрыт (стили по умолчанию visibility hidden)
        if (modalOverlay) {
            modalOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    })();

    /*
    Animation hero for main page
    */
   import { NeatGradient } from "https://esm.sh/@firecms/neat@0.8.0";

    const canvas = document.getElementById("bg");
    const hero = document.querySelector(".hero");

    if (canvas && hero) {
      const gradient = new NeatGradient({
        ref: canvas,
        colors: [
          { color: "#040F2A", enabled: true },
          { color: "#070081", enabled: true },
          { color: "#230058", enabled: false },
          { color: "#610099", enabled: true },
          { color: "#EE9B00", enabled: false }
        ],
        speed: 4.5,
        horizontalPressure: 5,
        verticalPressure: 7,
        waveFrequencyX: 2,
        waveFrequencyY: 2,
        waveAmplitude: 8,
        shadows: 6,
        highlights: 5,
        colorBrightness: 1.4,
        colorSaturation: 7,
        colorBlending: 10,
        backgroundColor: "#000",
        backgroundAlpha: 1,
        resolution: 1
      });

      const mouse = {
        mouseDistortionStrength: 0.35,
        mouseDistortionRadius: 0.25,
        mouseDecayRate: 0.96,
        mouseDarken: 0.2
      };

      let targetX = 0.5;
      let targetY = 0.5;
      let currentX = 0.5;
      let currentY = 0.5;
      let active = false;

      const base = {
        waveAmplitude: 8,
        shadows: 6,
        highlights: 5,
        colorBrightness: 1.4
      };

      function setTargetFromEvent(event) {
        const rect = hero.getBoundingClientRect();
        targetX = (event.clientX - rect.left) / rect.width;
        targetY = (event.clientY - rect.top) / rect.height;
        targetX = Math.max(0, Math.min(1, targetX));
        targetY = Math.max(0, Math.min(1, targetY));
      }

      hero.addEventListener("mouseenter", () => {
        active = true;
      });

      hero.addEventListener("mousemove", setTargetFromEvent);

      hero.addEventListener("mouseleave", () => {
        active = false;
        targetX = 0.5;
        targetY = 0.5;
      });

      function animateMouseReaction() {
        const lerp = active ? (1 - mouse.mouseDecayRate) : 0.03;
        currentX += (targetX - currentX) * lerp;
        currentY += (targetY - currentY) * lerp;

        const dx = (currentX - 0.5) * 2;
        const dy = (currentY - 0.5) * 2;
        const dist = Math.hypot(dx, dy);
        const radius = Math.max(0.001, mouse.mouseDistortionRadius * 2);
        const influence = active
          ? Math.max(0, 1 - dist / radius) * mouse.mouseDistortionStrength
          : 0;

        gradient.yOffset = currentY * 2200;
        gradient.waveAmplitude = base.waveAmplitude + influence * 6;
        gradient.shadows = base.shadows + influence * 2;
        gradient.highlights = base.highlights + influence * 1.5;
        gradient.colorBrightness = base.colorBrightness - influence * mouse.mouseDarken * 0.8;

        requestAnimationFrame(animateMouseReaction);
      }

      requestAnimationFrame(animateMouseReaction);
    }

    
   /* ==============
    Swiper slider
    ==============*/
       document.addEventListener('DOMContentLoaded', function () {
      if (typeof Swiper === 'undefined') {
        console.error('Swiper не загружен!');
        return;
      }

let mainSwiper, thumbsSwiper;
let resizeTimeout = null;
const DESKTOP_BREAKPOINT = 1000;
const NARROW_MOBILE_BREAKPOINT = 480;
const MEDIUM_DESKTOP_MIN = 1200;
const MEDIUM_DESKTOP_MAX = 1400;
let currentMode = null;

function getMode() {
  const width = window.innerWidth;
  if (width < NARROW_MOBILE_BREAKPOINT) return 'narrow';
  else if (width < DESKTOP_BREAKPOINT) return 'mobile';
  else return 'desktop';
}

function getSlidesPerView() {
  const width = window.innerWidth;
  const mode = getMode();
  
  if (mode === 'narrow') return 'auto';
  if (width >= MEDIUM_DESKTOP_MIN && width <= MEDIUM_DESKTOP_MAX) return 3.15;
  return 2.5;
}

// --- Основной слайдер ---
mainSwiper = new Swiper('.swiper-main', {
  loop: false,
  slidesPerView: 1,
  spaceBetween: 10,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  thumbs: { swiper: null },
  on: {
    slideChange: function () {
      if (thumbsSwiper && !thumbsSwiper.destroyed) {
        thumbsSwiper.slideTo(this.activeIndex);
        setActiveThumb(this.activeIndex);
      }
      updateNavigation();
    }
  }
});

// --- Инициализация thumbnails ---
function initThumbsSwiper() {
  const newMode = getMode();
  const activeSlideIndex = mainSwiper ? mainSwiper.activeIndex : 0;

  if (thumbsSwiper && !thumbsSwiper.destroyed) {
    thumbsSwiper.destroy(true, true);
  }

  const isDesktop = newMode === 'desktop';
  const direction = isDesktop ? 'vertical' : 'horizontal';
  const slidesPerView = getSlidesPerView();

  thumbsSwiper = new Swiper('.swiper-thumbs-slider', {
    direction: direction,
    slidesPerView: slidesPerView,
    spaceBetween: 20,
    watchSlidesProgress: true,
    slideToClickedSlide: true,
    loop: false,
    navigation: {
      nextEl: '.thumbs-button-next',
      prevEl: '.thumbs-button-prev',
    },
    on: {
      init: function () {
        this.slideTo(activeSlideIndex);
        setActiveThumb(activeSlideIndex);
        updateNavigation();
      },
      slideChange: function () {
        if (mainSwiper && !mainSwiper.destroyed && !mainSwiper.animating) {
          mainSwiper.slideTo(this.activeIndex);
        }
      },
      click: function (swiper, event) {
        const clickedSlide = event.target.closest('.swiper-slide');
        if (clickedSlide) {
          const slideIndex = Array.from(swiper.slides).indexOf(clickedSlide);
          if (slideIndex !== -1 && mainSwiper && !mainSwiper.destroyed) {
            mainSwiper.slideTo(slideIndex);
          }
        }
      }
    }
  });

  if (mainSwiper && !mainSwiper.destroyed) {
    mainSwiper.thumbs.swiper = thumbsSwiper;
    if (mainSwiper.thumbs?.init) {
      mainSwiper.thumbs.init();
      mainSwiper.thumbs.update();
    }
  }
  currentMode = newMode;
}

function setActiveThumb(index) {
  const thumbsSlides = document.querySelectorAll('.swiper-thumbs-slider .swiper-slide');
  thumbsSlides.forEach((slide, i) => {
    slide.classList.toggle('swiper-slide-thumb-active', i === index);
  });
}

function updateNavigation() {
  const selectors = { prevMain: '.swiper-button-prev', nextMain: '.swiper-button-next', prevThumb: '.thumbs-button-prev', nextThumb: '.thumbs-button-next' };
  const els = {};
  for (const key in selectors) {
    els[key] = document.querySelector(selectors[key]);
    if (!els[key]) return;
  }
  if (mainSwiper && !mainSwiper.destroyed) {
    els.prevMain.classList.toggle('swiper-button-disabled', mainSwiper.isBeginning);
    els.nextMain.classList.toggle('swiper-button-disabled', mainSwiper.isEnd);
  }
  if (thumbsSwiper && !thumbsSwiper.destroyed) {
    els.prevThumb.classList.toggle('swiper-button-disabled', thumbsSwiper.isBeginning);
    els.nextThumb.classList.toggle('swiper-button-disabled', thumbsSwiper.isEnd);
  }
}

function handleResize() {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(() => {
    const newMode = getMode();
    const width = window.innerWidth;
    const isMediumDesktop = width >= MEDIUM_DESKTOP_MIN && width <= MEDIUM_DESKTOP_MAX;
    
    // Переинициализируем если изменился режим или мы вошли/вышли из диапазона 1200-1400
    if (currentMode !== newMode || isMediumDesktop) {
      initThumbsSwiper();
    }
  }, 200);
}

initThumbsSwiper();
window.addEventListener('resize', handleResize);

document.querySelectorAll('.swiper-thumbs-slider img').forEach(img => {
  if (img.complete) {
    if (thumbsSwiper && !thumbsSwiper.destroyed) thumbsSwiper.update();
  } else {
    img.addEventListener('load', () => {
      if (thumbsSwiper && !thumbsSwiper.destroyed) thumbsSwiper.update();
    });
  }
});

window.addEventListener('beforeunload', () => {
  window.removeEventListener('resize', handleResize);
  thumbsSwiper?.destroy?.(true, true);
  mainSwiper?.destroy?.(true, true);
});

/*============
FAQ Selection
===========*/


 /* FAQ section */
      const faqQuestions = document.querySelectorAll('.faq-question');
      if (faqQuestions.length > 0) {
        faqQuestions.forEach(button => {
          button.addEventListener('click', () => {
            const item = button.closest('.faq-item');
            item.classList.toggle('active');
            const answer = item.querySelector('.faq-answer');
            if (item.classList.contains('active')) {
              setTimeout(() => { answer.style.maxHeight = answer.scrollHeight + 'px'; }, 10);
            } else {
              answer.style.maxHeight = '0px';
            }
          });
        });
      }
      /* CTA block for mobile */
      const openButton = document.getElementById('cta-open-mob');
      const overlayCta = document.querySelector('.cta-mob-overlay');
      const desktopCta = document.querySelector('.top-product-cta');

      if (openButton && overlayCta && desktopCta) {
        function showModal() {
          overlayCta.classList.add('active');
          desktopCta.classList.add('modal-active');
        }
        function hideModal() {
          overlayCta.classList.remove('active');
          desktopCta.classList.remove('modal-active');
        }
        openButton.addEventListener('click', function (e) { e.preventDefault(); showModal(); });
        document.addEventListener('click', function (e) {
          if (desktopCta.classList.contains('modal-active') && !desktopCta.contains(e.target) && !openButton.contains(e.target)) {
            hideModal();
          }
        });
        if ('ontouchstart' in window) {
          openButton.addEventListener('touchend', function (e) { e.preventDefault(); showModal(); });
        }
      }

      /* Sticky CTA logic */
      const cta = document.querySelector('.top-cta-desktop');
      const footer = document.querySelector('.contacts-bottom');
      if (cta && footer) {
        const OFFSET_TOP = 48;
        const OFFSET_BOTTOM = 120;
        const HYSTERESIS = 10;
        let state = 'normal';
        let placeholder = null;
        let originalTop = null;
        let frozenTop = null;

        function recordOriginalPosition() {
          const rect = cta.getBoundingClientRect();
          originalTop = rect.top + window.scrollY;
        }
        function shouldRun() { return window.innerWidth >= 1000; }

        function updatePosition() {
          if (!shouldRun()) {
            cta.classList.remove('fixed', 'absolute');
            cta.style.removeProperty('top');
            if (placeholder) { placeholder.remove(); placeholder = null; }
            state = 'normal';
            return;
          }
          cta.parentNode.style.position = 'relative';
          const scrollY = window.scrollY;
          const ctaRect = cta.getBoundingClientRect();
          const footerRect = footer.getBoundingClientRect();
          const ctaHeight = ctaRect.height;
          const footerAbsoluteTop = footerRect.top + scrollY;
          const fixStartY = originalTop - OFFSET_TOP;
          const freezeStartY = footerAbsoluteTop - OFFSET_BOTTOM - ctaHeight;
          let newState;

          if (state === 'normal') newState = scrollY >= fixStartY + HYSTERESIS ? 'fixed' : 'normal';
          else if (state === 'fixed') {
            if (scrollY < fixStartY - HYSTERESIS) newState = 'normal';
            else if (scrollY >= freezeStartY + HYSTERESIS) { newState = 'absolute'; frozenTop = freezeStartY; }
            else newState = 'fixed';
          } else if (state === 'absolute') {
            newState = scrollY < freezeStartY - HYSTERESIS ? 'fixed' : 'absolute';
          }

          if (newState !== state) {
            applyState(newState);
            state = newState;
          }
        }

        function applyState(newState) {
          if (newState === 'normal') {
            cta.classList.remove('fixed', 'absolute');
            cta.style.removeProperty('top');
            if (placeholder) { placeholder.remove(); placeholder = null; }
          } else if (newState === 'fixed') {
            if (!placeholder) {
              const ctaRect = cta.getBoundingClientRect();
              placeholder = document.createElement('div');
              placeholder.style.width = ctaRect.width + 'px';
              placeholder.style.height = ctaRect.height + 'px';
              placeholder.style.visibility = 'hidden';
              cta.parentNode.insertBefore(placeholder, cta);
            }
            cta.classList.remove('absolute');
            cta.classList.add('fixed');
            cta.style.removeProperty('top');
          } else if (newState === 'absolute') {
            const parentRect = cta.parentNode.getBoundingClientRect();
            const parentTop = parentRect.top + window.scrollY;
            const relTop = frozenTop - parentTop;
            cta.classList.remove('fixed');
            cta.classList.add('absolute');
            cta.style.top = relTop + 'px';
          }
        }

        if (shouldRun()) {
          recordOriginalPosition();
          cta.parentNode.style.position = 'relative';
        }
        updatePosition();
        window.addEventListener('scroll', updatePosition, { passive: true });
        window.addEventListener('resize', function () {
          if (shouldRun()) { recordOriginalPosition(); cta.parentNode.style.position = 'relative'; }
          updatePosition();
        });
      }

      /* ====== ЛАЙТБОКС (МОДАЛЬНАЯ ГАЛЕРЕЯ) ====== */
      (function initGalleryModal() {
        const modal = document.getElementById('galleryModal');
        if (!modal) return;

        const overlay = modal.querySelector('.gallery-modal__overlay');
        const closeBtn = modal.querySelector('.gallery-modal__close');
        const content = modal.querySelector('.gallery-modal__content');
        const swiperWrapper = modal.querySelector('.gallery-modal__swiper .swiper-wrapper');

        let modalSwiper = null;

        function getMainSlidesImages() {
          const slides = document.querySelectorAll('.swiper-main .swiper-slide img');
          return Array.from(slides).map(img => img.src);
        }

        function populateModal() {
          const images = getMainSlidesImages();
          swiperWrapper.innerHTML = images.map(src => `<div class="swiper-slide"><img src="${src}" alt=""></div>`).join('');
        }

        function openModal(startIndex = 0) {
          populateModal();
          modal.classList.add('is-open');
          document.body.style.overflow = 'hidden'; // Блокируем скролл страницы

          if (modalSwiper && !modalSwiper.destroyed) {
            modalSwiper.destroy(true, true);
          }

          modalSwiper = new Swiper('.gallery-modal__swiper', {
            initialSlide: startIndex,
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            grabCursor: true,
            navigation: {
              nextEl: '.gallery-modal__next',
              prevEl: '.gallery-modal__prev',
            },
            pagination: {
              el: '.gallery-modal__pagination',
              clickable: true,
            },
            keyboard: {
              enabled: true,
            },
          });
        }

        function closeModal() {
          modal.classList.remove('is-open');
          document.body.style.overflow = ''; // Возвращаем скролл
          if (modalSwiper && !modalSwiper.destroyed) {
            modalSwiper.destroy(true, true);
            modalSwiper = null;
          }
        }

        // 1. Клик по изображению в основном слайдере
        document.addEventListener('click', function (e) {
          const img = e.target.closest('.swiper-main .swiper-slide img');
          if (!img) return;

          const activeSlide = document.querySelector('.swiper-main .swiper-slide-active');
          const allSlides = Array.from(document.querySelectorAll('.swiper-main .swiper-slide'));
          const index = allSlides.indexOf(activeSlide);

          openModal(index >= 0 ? index : 0);
        });

        // 2. Закрытие по крестику
        closeBtn.addEventListener('click', closeModal);

        // 3. Закрытие по клику на затемнённый фон
        overlay.addEventListener('click', closeModal);

        // 4. Закрытие по клику на пустое место внутри контента (но НЕ по слайдеру/стрелкам/пагинации)
        content.addEventListener('click', function (e) {
          if (!e.target.closest('.swiper-slide, .swiper-button-prev, .swiper-button-next, .swiper-pagination')) {
            closeModal();
          }
        });

        // 5. Закрытие по клавише Esc
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
          }
        });
      })();

    });

    /*
    =====================
    видео-блоки
    =====================
    */
   (function() {
    // Функция инициализации всех видео-блоков
    function initVideoBlocks() {
        // Находим все контейнеры с видео
        const videoContainers = document.querySelectorAll('.vd-gen-eg_col-item_video');
        
        // Если контейнеров нет - выходим без ошибок
        if (!videoContainers.length) {
            return;
        }
        
        // Инициализируем каждый контейнер
        videoContainers.forEach(function(container) {
            const previewWrapper = container.querySelector('.eg-vd-preview-wrapper');
            const videoWrapper = container.querySelector('.eg-vd-video-iframe-wrapper');
            const iframe = container.querySelector('.eg-vd-youtube-iframe');
            
            // Создаем функцию воспроизведения для конкретного контейнера
            function playVideo() {
                if (previewWrapper) {
                    previewWrapper.style.display = 'none';
                }
                
                if (videoWrapper) {
                    videoWrapper.style.display = 'block';
                }
                
                if (iframe) {
                    const currentSrc = iframe.src;
                    let newSrc = currentSrc;
                    
                    // Добавляем autoplay=1
                    if (currentSrc.includes('autoplay=0')) {
                        newSrc = currentSrc.replace('autoplay=0', 'autoplay=1');
                    } else if (!currentSrc.includes('autoplay=1')) {
                        newSrc = currentSrc + (currentSrc.includes('?') ? '&' : '?') + 'autoplay=1';
                    }
                    
                    // Добавляем mute=1 для гарантированного автовоспроизведения
                    if (!newSrc.includes('mute=1')) {
                        newSrc += '&mute=1';
                    }
                    
                    iframe.src = newSrc;
                }
            }
            
            // Добавляем обработчик клика на превью
            if (previewWrapper) {
                previewWrapper.style.cursor = 'pointer';
                previewWrapper.addEventListener('click', playVideo);
            }
        });
    }
    
    // Запускаем после загрузки DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVideoBlocks);
    } else {
        initVideoBlocks();
    }
})();