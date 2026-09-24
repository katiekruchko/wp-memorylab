<?php
/**
 * Template Name: Catalog
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package memorylab
 */

get_header();
?>

<main id="primary" class="page secondary-page site-main">

    <section class="catalog-main container" id="catalog-page">
      <div class="search-product-header">
        <div class="search-product-h"><h1 class="h1-black">Все интерактивы</h1></div>
        <div class="search-product-block">
          <div class="search-product-wrap">
            <div class="search-input-icon">
                <img src="<?php echo get_template_directory_uri(); ?>/images/search-icon.svg" alt="поиск интерактива">
            </div>
            <input type="text"
                  class="search-input"
                  id="product-search"
                  placeholder="Название интерактива, категория..."
                  autocomplete="off" />
          </div>
        </div>
        </div>
      </div>
      <div class="catalog-wrap">
        <!-- <div class="catalog-filter_wrap">
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
        </div> -->
        <div class="catalog-filter_wrap">
          <div class="catalog-filter">
            <div class="filters">
              <button class="filter-btn active" data-filter="all">Все интерактивы</button>
              <?php
              // ============================================
              // ДИНАМИЧЕСКИЙ ВЫВОД КАТЕГОРИЙ
              // ============================================
              // Только те категории, у которых есть посты "staff".
              // Uncategorized — исключён.
              // Порядок — по term_id (порядок создания в админке).
              // ============================================

              $categories = get_terms( array(
                  'taxonomy'   => 'category',
                  'hide_empty' => true,
                  'exclude'    => array( (int) get_option( 'default_category' ) ),
                  'orderby'    => 'term_id',
                  'order'      => 'ASC',
              ) );

              if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
                  foreach ( $categories as $cat ) {

                      // Проверяем, есть ли посты staff в этой категории
                      $staff_posts = get_posts( array(
                          'post_type'      => 'staff',
                          'post_status'    => 'publish',
                          'posts_per_page' => 1,
                          'fields'         => 'ids',
                          'tax_query'      => array(
                              array(
                                  'taxonomy' => 'category',
                                  'field'    => 'slug',
                                  'terms'    => $cat->slug,
                              ),
                          ),
                      ) );

                      if ( empty( $staff_posts ) ) {
                          continue; // нет постов staff — пропускаем
                      }

                      printf(
                          '<button class="filter-btn" data-filter="%s">%s</button>',
                          esc_attr( $cat->slug ),
                          esc_html( $cat->name )
                      );
                  }
              }
              ?>
            </div>
          </div>
        </div>
        <!-- Карточки -->
        <div class="cards-grid" id="cards-grid">
          <?php
          $paged = get_query_var('paged') ? get_query_var('paged') : 1;
          $args = array(
              'post_type'      => 'staff',
              'posts_per_page' => MEMORYLAB_PAGE_SIZE,
              'post_status'    => 'publish',
              'orderby'        => 'date',
              'order'          => 'DESC',
              'paged'          => $paged,
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
                    id="load-more"
                    data-page="1"
                    data-max-pages="<?php echo $staff_query->max_num_pages; ?>"
                    data-post-type="staff">
              Показать ещё
            </button>
            <div class="loading-spinner" id="loading-spinner" style="display: none;">
              Загрузка...
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

<?php
get_footer();

