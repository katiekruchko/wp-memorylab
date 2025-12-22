<?php
/**
 * The main template file
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
                  src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="" /></span></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Каталог на главной -->
    <section class="catalog-main container" id="home-catalog">
      <div class="catalog-header">
        <h2 class="h2-catalog">
          Каталог <span class="text-gradient">оборудования</span>
        </h2>
      </div>
      <div class="catalog-wrap">
        <div class="catalog-filter_wrap">
          <div class="catalog-filter">
            <div class="filters">
              <button class="filter-btn active" data-filter="all">Все интерактивы</button>
              <button class="filter-btn" data-filter="hit">Хиты сезона 🔥</button>
              <button class="filter-btn" data-filter="fotobudki">Фотобудки</button>
              <button class="filter-btn" data-filter="ai-interactivy">AI Интерактивы 🤖</button>
              <button class="filter-btn" data-filter="new">Новинки ⭐</button>
              <button class="filter-btn" data-filter="foto">Фото</button>
              <button class="filter-btn" data-filter="video">Видео</button>
              <button class="filter-btn" data-filter="muzykalnye">Музыкальные</button>
              <button class="filter-btn" data-filter="timbilding">Тимбилдинг</button>
              <button class="filter-btn" data-filter="korporativ">Корпоратив</button>
              <button class="filter-btn" data-filter="konferentsiya">Конференция</button>
            </div>
          </div>
        </div>

        <!-- Карточки -->
        <div class="cards-grid" id="home-cards-grid">
          <?php
          // Аргументы для первой загрузки на главной
          $args = array(
              'post_type'      => 'staff',
              'posts_per_page' => 6, // Показываем по 6 карточек на главной
              'post_status'    => 'publish',
              'orderby'        => 'date',
              'order'          => 'DESC',
          );

          $staff_query = new WP_Query($args);

          if ($staff_query->have_posts()) :
              while ($staff_query->have_posts()) : $staff_query->the_post();
                  get_template_part('template-parts/content', 'card');
              endwhile;
          else :
              echo '<p>Нет доступных интерактивов.</p>';
          endif;

          wp_reset_postdata();
          ?>
        </div>

        <div class="btn-catalog">
          <?php if ($staff_query->max_num_pages > 1) : ?>
            <button class="btn-grey btn-more"
                    id="home-load-more"
                    data-page="1"
                    data-max-pages="<?php echo $staff_query->max_num_pages; ?>"
                    data-post-type="staff">
              Показать ещё
            </button>
            <div class="loading-spinner" id="home-loading-spinner" style="display: none;">
              Загрузка...
            </div>
          <?php endif; ?>
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
  <!-- Результаты AI будут загружаться здесь -->
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
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-1.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-2.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-3.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-4.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-5.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-6.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-7.png" alt="" />
            </div>
          </div>

          <!-- Второй ряд -->
          <div class="row-insta row-insta-2">
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-8.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-9.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-10.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-11.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-12.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-13.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-14.png" alt="" />
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
          <img src="<?php echo get_template_directory_uri(); ?>/images/qr-code-line.svg" alt="" />
        </div>
      </div>
    </section>
  </main>

<?php
get_footer();
