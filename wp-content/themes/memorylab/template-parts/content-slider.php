<?php
/**
 * For single-staff page, section slider
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */

// Получаем текущую запись
$pod = pods(get_post_type(), get_the_ID());
$slider_images = $pod->field('slider-foto-main');

if (!$slider_images || !is_array($slider_images)) {
    $slider_images = [];
}
?>

<div class="swiper-container">
  <!-- main slider -->
  <div class="swiper swiper-main">
    <div class="swiper-wrapper">
      <?php if (!empty($slider_images)): ?>
        <?php foreach ($slider_images as $index => $image): ?>
          <?php
          $image_id = 0;
          $large_url = '';
          $full_url = '';
          
          // Определяем ID изображения
          if (is_array($image) && isset($image['ID'])) {
              $image_id = $image['ID'];
          } elseif (is_numeric($image)) {
              $image_id = $image;
          }
          
          // Получаем URL для разных размеров
          if ($image_id) {
              $large_url = wp_get_attachment_image_url($image_id, 'large');
              $full_url = wp_get_attachment_image_url($image_id, 'full');
              $medium_url = wp_get_attachment_image_url($image_id, 'medium');
          } else {
              // Fallback на pods_image_url если не получили ID
              $large_url = pods_image_url($image, 'large');
              $full_url = pods_image_url($image, 'full');
          }
          ?>
          
          <div class="swiper-slide">
            <img src="<?php echo esc_url($large_url); ?>" 
                 srcset="<?php 
                    if ($medium_url) echo esc_url($medium_url) . ' 768w, ';
                    if ($large_url) echo esc_url($large_url) . ' 1024w, ';
                    if ($full_url) echo esc_url($full_url) . ' 1920w';
                 ?>"
                 sizes="(max-width: 768px) 100vw, (max-width: 1200px) 80vw, 1200px"
                 alt="Slide <?php echo $index + 1; ?>"
                 loading="lazy"
                 width="1200"
                 height="600">
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="swiper-slide">
          <img src="https://via.placeholder.com/1200x600?text=No+Images" 
               alt="No images"
               loading="lazy">
        </div>
      <?php endif; ?>
    </div>

    <!-- навигация -->
	<div class="swiper-button-prev"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow-prev-slider.svg" alt=""></div>
              <div class="swiper-button-next"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow-next-slider.svg" alt=""></div>
  </div>

  <!-- thumbnails -->
  <div class="swiper-thumbs-container">
    <div class="swiper swiper-thumbs-slider">
      <div class="swiper-wrapper">
        <?php foreach ($slider_images as $index => $image): ?>
          <?php 
          $thumb_url = '';
          if (is_array($image) && isset($image['ID'])) {
              $thumb_url = wp_get_attachment_image_url($image['ID'], 'large');
          } elseif (is_numeric($image)) {
              $thumb_url = wp_get_attachment_image_url($image, 'large');
          } else {
              $thumb_url = pods_image_url($image, 'large');
          }
          ?>
          <div class="swiper-slide">
            <div class="wrap-img-thumb">
              <img src="<?php echo esc_url($thumb_url); ?>" 
                   alt="Thumb <?php echo $index + 1; ?>"
                   loading="lazy">
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- навигация миниатюр -->
	<div class="thumbs-button-prev"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow-prev-slider.svg" alt=""></div>
              <div class="thumbs-button-next"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow-next-slider.svg" alt=""></div>
  </div>
</div>
          <div class="top-product-cta ">
            <div class="product-cta top-cta-desktop">
              <div class="prod-cta-wrap">
                <div class="prod-cta-h">Хотите заказать?</div>
                <div class="prod-cta-desc">Выберите любой предпочитаемый способ для связи</div>
                <div class="prod-cta-btns">
                  <a class="prod-cta-contact" href="/">Телеграм <img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt=""></a>
                  <a class="prod-cta-contact" href="/">Viber <img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt=""></a>
                  <a class="prod-cta-tel" href="tel:+375298210398">+375 29 821 03 98</a>
                </div>
              </div>
            </div>
          </div>