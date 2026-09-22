<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package memorylab
 */

get_header();
?>
<div class="mm-overlay"></div>
  <div class="cta-mob-overlay"></div>
  <div id="cta-open-mob" class="cta-open-btn">
    <img src="<?php echo get_template_directory_uri(); ?>/images/cta-icon-violet.svg" alt="">
  </div>

<div class="staff-single">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="primary" class="page secondary-page site-main">
    <div class="page-product container">
      <div class="breadcrumbs-wrap">
        <a href="/catalog/" class="breadcrumbs-item">Все интерактивы</a><span class="breadcrumbs-divider">/</span><span
          class="breadcrumbs-item-active"><?php the_title(); ?></span>
      </div>
      <div class="page-product-content">
        <div class="top-page-product">
          
		  <?php get_template_part( 'template-parts/content-slider' ); ?>

          <div class="product-main-content">
            <section class="inner-content">
              <h1 class="h1-black"><?php the_title(); ?></h1>
			  <div class="content">
            <?php the_content(); ?>
        </div>
            </section>
		
			<?php get_template_part( 'template-parts/content-mehanika' ); ?>
			<?php get_template_part( 'template-parts/content-rent' ); ?>
			<?php get_template_part( 'template-parts/content-addition' ); ?>
      <?php get_template_part( 'template-parts/content-example' ); ?>
			<?php get_template_part( 'template-parts/content-service' ); ?>
			<?php get_template_part( 'template-parts/content-eventfoto' ); ?>
			<?php get_template_part( 'template-parts/content-faq' ); ?>
			
		
          
          <section class="related-products gap-section">
            <div class="catalog-header">
              <h2 class="h2-catalog">
                Вам может <span class="text-gradient">понравиться</span>
              </h2>
			  <?php get_template_part( 'template-parts/content-related' ); ?>
              <div class="btn-catalog">
                <a href="/catalog" class="btn-grey btn-more">Показать ещё</a>
              </div>
            </div>
          </section>
        </div>
      </div>

    </div>

    </div>
  </main>
    <?php endwhile; endif; ?>
</div>
<!-- Модальное окно с галереей (Лайтбокс) -->
  <div class="gallery-modal" id="galleryModal">
    <div class="gallery-modal__overlay"></div>
    <div class="gallery-modal__content">
      <button class="gallery-modal__close" aria-label="Закрыть">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
      </button>

      <div class="swiper gallery-modal__swiper">
        <div class="swiper-wrapper">
          <!-- Слайды будут автоматически добавлены через JS -->
        </div>
        <div class="swiper-button-prev gallery-modal__prev"></div>
        <div class="swiper-button-next gallery-modal__next"></div>
        <div class="swiper-pagination gallery-modal__pagination"></div>
      </div>
    </div>
  </div>

<?php
get_footer();
