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
          $args = array(
              'post_type'      => 'staff',
              'posts_per_page' => 8,
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
                <?php
                $event_types = get_terms(array(
                  'taxonomy' => 'event_type',
                  'hide_empty' => true,
                ));

                if (!empty($event_types) && !is_wp_error($event_types)) {
                  foreach ($event_types as $term) {
                    echo '<div class="ai-dropdown-item" data-value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div>';
                  }
                }
                ?>
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
                <?php
                $durations = get_terms(array(
                  'taxonomy' => 'duration',
                  'hide_empty' => true,
                ));

                if (!empty($durations) && !is_wp_error($durations)) {
                  foreach ($durations as $term) {
                    echo '<div class="ai-dropdown-item" data-value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div>';
                  }
                }
                ?>
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
                <?php
                $formats = get_terms(array(
                  'taxonomy' => 'format',
                  'hide_empty' => true,
                ));

                if (!empty($formats) && !is_wp_error($formats)) {
                  foreach ($formats as $term) {
                    echo '<div class="ai-dropdown-item" data-value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div>';
                  }
                }
                ?>
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
        <div class="cards-grid" id="ai-results-grid">
        <!-- Результаты AI будут загружаться здесь -->
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
