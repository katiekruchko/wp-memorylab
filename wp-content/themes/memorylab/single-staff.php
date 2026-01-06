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


<?php
get_footer();
