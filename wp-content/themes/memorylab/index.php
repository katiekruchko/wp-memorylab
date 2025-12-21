<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package memorylab
 */

get_header();
?>
<main id="primary" class="page main-page site-main">
    <div class="main-hero_3d">
      <!-- <script type="module" src="https://unpkg.com/@splinetool/viewer@1.10.99/build/spline-viewer.js"></script>
    <div class="spline-wrap"><spline-viewer url="https://prod.spline.design/4S6qrpx2vkEAace7/scene.splinecode"></spline-viewer></div> -->
    </div>
    <div class="main-hero">
      <!-- 3D-модель -->

      <div class="main-hero_wrap">
        <h1 class="h1-main-hero">
          <span class="part1">Аренда</span>
          <span class="part2"> интерактивного</span>
          <span class="part3"> оборудования</span>
          <span class="part4"> для мероприятий</span>
        </h1>
        <div class="main-hero_desc">
          Мы поможем вам подобрать интерактив, забрендируем, доставим,
          смонтируем, и смодерируем на площадке
        </div>
        <div class="main-hero_ctas">
          <div class="main-hero_cta_color">
            <a class="btn_color btn" href="/catalog">Перейти в каталог</a>
          </div>
          <div class="main-hero_cta_tg">
            <a class="btn_light btn btn-icon" href="/"><span>Написать в Телеграм</span><span><img class="a-tg"
                  src="./images/telegram.svg" alt="" /></span></a>
          </div>
        </div>
      </div>
    </div>
    <section class="catalog-main container">
      <div class="catalog-header">
        <h2 class="h2-catalog">
          Каталог <span class="text-gradient">оборудования</span>
        </h2>
      </div>
      <div class="catalog-wrap">
        <div class="catalog-filter_wrap">
          <div class="catalog-filter">
            <div class="filters">
              <button class="filter-btn active">Все интерактивы</button>
              <button class="filter-btn">Хиты сезона 🔥</button>
              <button class="filter-btn">Фотобудки</button>
              <button class="filter-btn">AI Интерактивы 🤖</button>
              <button class="filter-btn">Новинки ⭐</button>
              <button class="filter-btn">Фото</button>
              <button class="filter-btn">Видео</button>
              <button class="filter-btn">Музыкальные</button>
              <button class="filter-btn">Тимбилдинг</button>
              <button class="filter-btn">Корпоратив</button>
              <button class="filter-btn">Конференция</button>
            </div>
          </div>
        </div>
        <!-- Карточки -->
        <div class="cards-grid">
          <!-- Карточка 1 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-1.png" alt="Фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Фотобудка</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>

          <!-- Карточка 2 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-2.png" alt="Видеоспинер" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Видеоспинер</h3>
              <div class="card-desc">
                Создаёт головокружительные видеоклипы с эффектом замедления,
                готовые для размещения в социальных...
              </div>
            </div>
          </div>

          <!-- Карточка 3 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-3.png" alt="AI-фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">AI-фотобудка</h3>
              <div class="card-desc">
                Многоновая печать фотографий обработанных нейросетью в режиме
                реального времени по заданным
              </div>
            </div>
          </div>

          <!-- Карточка 4 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-4.png" alt="Скетч-бот" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Скетч-бот</h3>
              <div class="card-desc">
                Рисует портрет в режиме реального времени по фотографии
              </div>
            </div>
          </div>

          <!-- Карточка 5 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-5.png" alt="Селфи-зеркало" class="card-image" />
              <div class="badge-wrap">
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Селфи-зеркало</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>
          <!--  -->
          <!-- Карточка 6 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-1.png" alt="Фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Фотобудка</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>

          <!-- Карточка 7 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-2.png" alt="Видеоспинер" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Видеоспинер</h3>
              <div class="card-desc">
                Создаёт головокружительные видеоклипы с эффектом замедления,
                готовые для размещения в социальных...
              </div>
            </div>
          </div>

          <!-- Карточка 8 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-3.png" alt="AI-фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">AI-фотобудка</h3>
              <div class="card-desc">
                Многоновая печать фотографий обработанных нейросетью в режиме
                реального времени по заданным
              </div>
            </div>
          </div>

          <!-- Карточка 9 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-4.png" alt="Скетч-бот" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Скетч-бот</h3>
              <div class="card-desc">
                Рисует портрет в режиме реального времени по фотографии
              </div>
            </div>
          </div>

          <!-- Карточка 10 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="./images/img-5.png" alt="Селфи-зеркало" class="card-image" />
              <div class="badge-wrap">
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Селфи-зеркало</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>
        </div>
        <div class="btn-catalog">
          <button class="btn-grey btn-more">Показать ещё</button>
        </div>
      </div>
    </section>
  <!-- AI Calculator -->
  <section class="ai-service-section">
  <div class="ai-service-wrap container">
    <h2 class="ai-service-h">
      Выбор <span class="ai-h2-gradient">нейросети</span>
    </h2>
    <div class="ai-service-desc">
      Наш калькулятор на основе нейросети поможет вам подобрать самое
      оптимальное оборудование для мероприятия
    </div>

    <form class="ai-form" id="ai-form">
      <div class="ai-form-row">
        <!-- Тип мероприятия -->
        <div class="ai-select-wrapper">
          <div class="ai-custom-select" id="event-type-trigger" data-name="event_type">
            <span>Тип мероприятия</span>
          </div>
          <input type="hidden" name="event_type" value="">
          <div class="ai-error-message">Выберите значение из списка</div>
          <div class="ai-dropdown" id="event-type-dropdown">
            <div class="ai-dropdown-wrap">
              <div class="ai-dropdown-item" data-value="Тип мероприятия 1">Тип мероприятия 1</div>
              <div class="ai-dropdown-item" data-value="Тип мероприятия 2">Тип мероприятия 2</div>
              <div class="ai-dropdown-item" data-value="Тип мероприятия 3">Тип мероприятия 3</div>
              <div class="ai-dropdown-item" data-value="Тип мероприятия 4">Тип мероприятия 4</div>
              <div class="ai-dropdown-item" data-value="Тип мероприятия 5">Тип мероприятия 5</div>
              <div class="ai-dropdown-item" data-value="Тип мероприятия 6">Тип мероприятия 6</div>
            </div>
          </div>
        </div>
    
        <!-- Длительность -->
        <div class="ai-select-wrapper">
          <div class="ai-custom-select" id="duration-trigger" data-name="duration">
            <span>Длительность</span>
          </div>
          <input type="hidden" name="duration" value="">
          <div class="ai-error-message">Выберите значение из списка</div>
          <div class="ai-dropdown" id="duration-dropdown">
            <div class="ai-dropdown-wrap">
              <div class="ai-dropdown-item" data-value="Длительность 1">Длительность 1</div>
              <div class="ai-dropdown-item" data-value="Длительность 2">Длительность 2</div>
              <div class="ai-dropdown-item" data-value="Длительность 3">Длительность 3</div>
              <div class="ai-dropdown-item" data-value="Длительность 4">Длительность 4</div>
              <div class="ai-dropdown-item" data-value="Длительность 5">Длительность 5</div>
              <div class="ai-dropdown-item" data-value="Длительность 6">Длительность 6</div>
              <div class="ai-dropdown-item" data-value="Длительность 7">Длительность 7</div>
              <div class="ai-dropdown-item" data-value="Длительность 8">Длительность 8</div>
            </div>
          </div>
        </div>
    
        <!-- Формат площадки -->
        <div class="ai-select-wrapper">
          <div class="ai-custom-select" id="format-trigger" data-name="format">
            <span>Формат площадки</span>
          </div>
          <input type="hidden" name="format" value="">
          <div class="ai-error-message">Выберите значение из списка</div>
          <div class="ai-dropdown" id="format-dropdown">
          <div class="ai-dropdown-wrap">
              <div class="ai-dropdown-item" data-value="Формат площадки 1">Формат площадки 1</div>
              <div class="ai-dropdown-item" data-value="Формат площадки 2">Формат площадки 2</div>
              <div class="ai-dropdown-item" data-value="Формат площадки 3">Формат площадки 3</div>
              <div class="ai-dropdown-item" data-value="Формат площадки 4">Формат площадки 4</div>
          </div>
          </div>
        </div>
    
        <!-- Слайдер -->
        <div class="ai-slider-container">
          <span class="ai-slider-label">Количество человек:</span>
          <div class="ai-slider-wrapper">
            <div class="ai-slider-value">200</div>
            <input type="range" min="50" max="1000" value="200" step="50" class="ai-slider" id="people-slider" name="people_count" />
            <div class="ai-slider-labels">
              <span><50</span>
              <span>1000+</span>
            </div>
          </div>
        </div>
    
        <div class="wrap-ai-btn">
          <button id="ai-submit-btn" class="ai-submit-btn" type="submit">Подобрать варианты</button>
        </div>
      </div>
    </form>

<div id="show-ai-results" class="ai-result">
  <div class="cards-grid">
    <!-- Карточка 1 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-1.png" alt="Фотобудка" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Фотобудка</h3>
        <div class="card-desc">
          Фотографирует и моментально печатает снимки с вашим логотипом
        </div>
      </div>
    </div>

    <!-- Карточка 2 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-2.png" alt="Видеоспинер" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Видеоспинер</h3>
        <div class="card-desc">
          Создаёт головокружительные видеоклипы с эффектом замедления,
          готовые для размещения в социальных...
        </div>
      </div>
    </div>

    <!-- Карточка 3 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-3.png" alt="AI-фотобудка" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">AI-фотобудка</h3>
        <div class="card-desc">
          Многоновая печать фотографий обработанных нейросетью в режиме
          реального времени по заданным
        </div>
      </div>
    </div>

    <!-- Карточка 4 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-4.png" alt="Скетч-бот" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Скетч-бот</h3>
        <div class="card-desc">
          Рисует портрет в режиме реального времени по фотографии
        </div>
      </div>
    </div>

    <!-- Карточка 5 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-5.png" alt="Селфи-зеркало" class="card-image" />
        <div class="badge-wrap">
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Селфи-зеркало</h3>
        <div class="card-desc">
          Фотографирует и моментально печатает снимки с вашим логотипом
        </div>
      </div>
    </div>
    <!--  -->
    <!-- Карточка 6 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-1.png" alt="Фотобудка" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Фотобудка</h3>
        <div class="card-desc">
          Фотографирует и моментально печатает снимки с вашим логотипом
        </div>
      </div>
    </div>

    <!-- Карточка 7 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-2.png" alt="Видеоспинер" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Видеоспинер</h3>
        <div class="card-desc">
          Создаёт головокружительные видеоклипы с эффектом замедления,
          готовые для размещения в социальных...
        </div>
      </div>
    </div>

    <!-- Карточка 8 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-3.png" alt="AI-фотобудка" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">AI-фотобудка</h3>
        <div class="card-desc">
          Многоновая печать фотографий обработанных нейросетью в режиме
          реального времени по заданным
        </div>
      </div>
    </div>

    <!-- Карточка 9 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-4.png" alt="Скетч-бот" class="card-image" />
        <div class="badge-wrap">
          <span class="badge new">Новинка</span>
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Скетч-бот</h3>
        <div class="card-desc">
          Рисует портрет в режиме реального времени по фотографии
        </div>
      </div>
    </div>

    <!-- Карточка 10 -->
    <div class="card">
      <div class="card-image-wrapper">
        <img src="./images/img-5.png" alt="Селфи-зеркало" class="card-image" />
        <div class="badge-wrap">
          <span class="badge hit">Хит 🔥</span>
        </div>
      </div>
      <div class="card-content">
        <h3 class="card-title">Селфи-зеркало</h3>
        <div class="card-desc">
          Фотографирует и моментально печатает снимки с вашим логотипом
        </div>
      </div>
    </div>
  </div>
</div>

  </div>
</section>
  <!-- End AI Calculator -->

    <!-- Instagram section -->
    <section class="instagram-section">
      <div class="container">
        <h2 class="h2-insta">
          <span class="text-gradient-inverse">Ещё больше</span> контента у
          нас<br />
          в <span class="text-gradient-inverse">Инстаграм</span>
        </h2>
      </div>

      <div class="instagram-wrap">
        <div class="instagram-grid-container">
          <!-- Первый ряд -->
          <div class="row-insta row-insta-1">
            <div class="instagram-item">
              <img src="./images/insta-1.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-2.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-3.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-4.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-5.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-6.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-7.png" alt="" />
            </div>
          </div>

          <!-- Второй ряд -->
          <div class="row-insta row-insta-2">
            <div class="instagram-item">
              <img src="./images/insta-8.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-9.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-10.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-11.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-12.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-13.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="./images/insta-14.png" alt="" />
            </div>
          </div>
        </div>
        <!-- Градиентный слой поверх изображений -->
        <div class="gradient-overlay"></div>
      </div>

      <!-- Кнопки поверх всего -->
      <div class="cta-insta">
        <button class="instagram-button btn">Перейти в инстаграм</button>
        <div class="qr-code">
          <img src="./images/qr-code-line.svg" alt="" />
        </div>
      </div>
    </section>
  </main>
 

<?php
get_footer();
