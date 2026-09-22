<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package memorylab
 */

?>
<section id="contacts" class="contacts-bottom">
    <div class="contacts-ml-wrap container">
      <div class="contacts-ml-1">
        <h2 class="contact-ml-h">
          Свяжитесь с нами
          <span class="text-gradient-inverse">любым удобным</span> для вас
          способом
        </h2>
        <div class="contact-ml-desc">
          Отвечаем на звонки моментально, а в Телеграм ещё быстрее
        </div>
      </div>
      <div class="contacts-ml-2">
        <div class="contact-person-wrap">
          <div class="contact-person-item">
            <div class="person-foto">
              <img src="<?php echo get_template_directory_uri(); ?>/images/Aleksei.png" alt="" />
            </div>
            <div class="person-data">
              <div class="person-data-name">Алексей</div>
              <div class="person-data-contact">
                <div class="person-data-tel">
                  <a href="tel:+375298210398">+375 29 821 03 98</a>
                </div>
                <div class="person-data-soc">
                  <a href="https://t.me/alexeueasy" class="person-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="" /></a>
                  <a href="viber://chat?number=375298210398" class="person-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt="" /></a>
                  <a href="https://wa.me/375298210398?" class="person-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/whatsapp.svg" alt="" /></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer class="footer-ml">
    <div class="footer-wrap container">
      <div class="footer-row">
        <div class="footer-col footer-col-1">
          <div class="footer-logo-wrap">
            <div class="footer-logo">
              <a href="/"><img cclass="img-footer-logo" src="<?php echo get_template_directory_uri(); ?>/images/logo-white.svg" alt="" /></a>
            </div>
          </div>
        </div>
        <div class="footer-col footer-col-2">
          <div class="footer-soc-wrap">
            <a href="https://t.me/alexeueasy" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="" /></a>
            <a href="iber://chat?number=375298210398" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt="" /></a>
            <a href="https://wa.me/375298210398?" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/whatsapp.svg" alt="" /></a>
            <a href="/" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/instagram.svg" alt="" /></a>
             <a href="mailto:memorylab.by@gmail.com" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/gmail.svg" alt="" /></a>
          </div>
        </div>
        <div class="footer-col footer-col-3">
          <div class="footer-menu">
          <?php
$footer_menu_items = wp_get_nav_menu_items('footer');

if ($footer_menu_items) : ?>
    <ul class="footer-menu-ul">
        <?php foreach ($footer_menu_items as $item) : ?>
            <li class="footer-menu-li">
                <a class="footer-menu-a" href="<?php echo esc_url($item->url); ?>">
                    <?php echo esc_html($item->title); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
          </div>
        </div>
        <div class="footer-col footer-col-4">
          <div class="footer-address">
            <div class="ip-name">ИП Санько Алексей Сергеевич</div>
            <div class="ip-info">
              УНП 193886881<br>
  Юридический адрес: г. Минск,<br> 
  пр-т. Газеты «Звязда», д 49, кв 142.,<br>
  220117
            </div>
<div class="ip-email">
  <a class="ip-email-a" href="mailto:memorylab.by@gmail.com">memorylab.by@gmail.com</a>
</div>
          </div>
        </div>
      </div>
      <div class="bottom-block">
        <div class="bottom-block-1">
         <div class="bottom-link"><a href="/">Политика конфиденциальности</a></div>
        </div>
        <div class="bottom-block-2">
          <div class="copyright-wrap">
            © Memorylab.by
          </div>
        </div>
      </div>
    </div>
  </footer>
  <script type="module" src="<?php echo get_template_directory_uri(); ?>/js/site.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof Swiper === 'undefined') {
        console.error('Swiper не загружен!');
        return;
      }

      let mainSwiper, thumbsSwiper;
      let resizeTimeout = null;
      const DESKTOP_BREAKPOINT = 1001;
      const NARROW_MOBILE_BREAKPOINT = 480; // ← ваш порог
      let currentMode = null; // 'narrow', 'mobile', 'desktop'

      function getMode() {
        const width = window.innerWidth;
        if (width < NARROW_MOBILE_BREAKPOINT) {
          return 'narrow'; // < 480px
        } else if (width < DESKTOP_BREAKPOINT) {
          return 'mobile'; // 480–999px
        } else {
          return 'desktop'; // ≥ 1000px
        }
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
        thumbs: {
          swiper: null
        },
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

        let slidesPerView;
        if (newMode === 'narrow') {
          slidesPerView = 'auto'; // ← автоподбор по ширине (с фикс шириной слайда в CSS)
        } else {
          slidesPerView = 2.5; // для 480–999px и, условно, desktop (хотя в вертикали это тоже работает)
        }

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

        // Связываем слайдеры
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
        const selectors = {
          prevMain: '.swiper-button-prev',
          nextMain: '.swiper-button-next',
          prevThumb: '.thumbs-button-prev',
          nextThumb: '.thumbs-button-next'
        };

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
          if (currentMode !== newMode) {
            console.log(`Режим обновлён: ${currentMode} → ${newMode}`);
            initThumbsSwiper();
          }
        }, 200);
      }

      // Инициализация
      initThumbsSwiper();
      window.addEventListener('resize', handleResize);

      // Обновление после загрузки изображений
      document.querySelectorAll('.swiper-thumbs-slider img').forEach(img => {
        if (img.complete) {
          if (thumbsSwiper && !thumbsSwiper.destroyed) thumbsSwiper.update();
        } else {
          img.addEventListener('load', () => {
            if (thumbsSwiper && !thumbsSwiper.destroyed) thumbsSwiper.update();
          });
        }
      });

      // Очистка
      window.addEventListener('beforeunload', () => {
        window.removeEventListener('resize', handleResize);
        thumbsSwiper?.destroy?.(true, true);
        mainSwiper?.destroy?.(true, true);
      });
    });

    /*
    -------------------
    faq section
    -------------------
    */
const faqQuestions = document.querySelectorAll('.faq-question');
if (faqQuestions.length > 0) {
  // Применяем обработчик клика к каждому вопросу
  faqQuestions.forEach(button => {
    button.addEventListener('click', () => {
      const item = button.closest('.faq-item');
      item.classList.toggle('active');

      const answer = item.querySelector('.faq-answer');
      if (item.classList.contains('active')) {
        // Задаём max-height после рендеринга, чтобы избежать отображения части ответа
        setTimeout(() => {
          answer.style.maxHeight = answer.scrollHeight + 'px';
        }, 10);
      } else {
        answer.style.maxHeight = '0px';
      }
    });
  });
}

/*
cta block for mobile
*/
document.addEventListener('DOMContentLoaded', function () {
  const openButton = document.getElementById('cta-open-mob');
  const overlay = document.querySelector('.cta-mob-overlay');
  const desktopCta = document.querySelector('.top-product-cta');

  if (!openButton || !overlay || !desktopCta) return;

  function showModal() {
    overlay.classList.add('active');
    desktopCta.classList.add('modal-active');
  }

  function hideModal() {
    overlay.classList.remove('active');
    desktopCta.classList.remove('modal-active');
  }

  // Открытие по клику на кнопку
  openButton.addEventListener('click', function (e) {
    e.preventDefault();
    showModal();
  });

  // Закрытие по клику вне блока .top-cta-desktop
  document.addEventListener('click', function (e) {
    if (
      desktopCta.classList.contains('modal-active') &&
      !desktopCta.contains(e.target) &&
      !openButton.contains(e.target)
    ) {
      hideModal();
    }
  });

  // Поддержка iOS: обработка touch для мгновенного отклика
  if ('ontouchstart' in window) {
    openButton.addEventListener('touchend', function (e) {
      e.preventDefault();
      showModal();
    });
  }
});
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const cta = document.querySelector('.top-cta-desktop');
      const footer = document.querySelector('.contacts-bottom');
  
      if (!cta || !footer) return;
  
      const OFFSET = 320;
      const HYSTERESIS = 10;
      let isFixed = false;
      let placeholder = null;
      let originalTop = null;
  
      // Запоминаем исходную позицию
      function recordOriginalPosition() {
        const rect = cta.getBoundingClientRect();
        originalTop = rect.top + window.scrollY;
      }
  
      // Условие: скрипт активен только при ширине ≥ 1000px
      function shouldRun() {
        return window.innerWidth >= 1000;
      }
  
      function updatePosition() {
        // Прекращаем работу, если ширина меньше 1000px
        if (!shouldRun()) {
          // Убираем фиксацию и placeholder, если они остались
          if (isFixed) {
            cta.classList.remove('fixed');
            if (placeholder) {
              placeholder.remove();
              placeholder = null;
            }
            isFixed = false;
          }
          return;
        }
  
        const scrollY = window.scrollY;
        const ctaRect = cta.getBoundingClientRect();
        const footerRect = footer.getBoundingClientRect();
  
        const ctaHeight = ctaRect.height;
        const footerAbsoluteTop = footerRect.top + scrollY;
  
        let shouldFix;
  
        if (isFixed) {
          const tooHigh = scrollY < originalTop - HYSTERESIS;
          const tooLow = scrollY + ctaHeight >= footerAbsoluteTop - OFFSET + HYSTERESIS;
          shouldFix = !tooHigh && !tooLow;
        } else {
          const belowOriginal = scrollY > originalTop + HYSTERESIS;
          const farFromFooter = scrollY + ctaHeight < footerAbsoluteTop - OFFSET - HYSTERESIS;
          shouldFix = belowOriginal && farFromFooter;
        }
  
        if (shouldFix && !isFixed) {
          if (!placeholder) {
            placeholder = document.createElement('div');
            placeholder.style.width = ctaRect.width + 'px';
            placeholder.style.height = ctaHeight + 'px';
            placeholder.style.visibility = 'hidden';
            cta.parentNode.insertBefore(placeholder, cta);
          }
          cta.classList.add('fixed');
          isFixed = true;
        } else if (!shouldFix && isFixed) {
          cta.classList.remove('fixed');
          if (placeholder) {
            placeholder.remove();
            placeholder = null;
          }
          isFixed = false;
        }
      }
  
      // Запуск при инициализации
      if (shouldRun()) {
        recordOriginalPosition();
      }
      updatePosition();
  
      // Слушатели: обновляем позицию при скролле и ресайзе
      window.addEventListener('scroll', updatePosition, { passive: true });
      window.addEventListener('resize', function () {
        if (shouldRun()) {
          recordOriginalPosition();
        }
        updatePosition();
      });
    });
  </script> -->
<script>
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
  </script>
  <script>
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
</script>

<?php wp_footer(); ?>

</body>
</html>
