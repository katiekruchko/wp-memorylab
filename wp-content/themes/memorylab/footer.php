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
  <script type="module" src="/js/site.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
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
  </script>


<?php wp_footer(); ?>

</body>
</html>
