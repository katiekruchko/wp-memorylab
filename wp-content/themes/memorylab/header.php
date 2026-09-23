<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package memorylab
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MemoryLab</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
 
  <!-- <link rel="stylesheet" href="/scss/index.css"> -->
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/scss/secondary.css?v=2">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/scss/main.css">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/scss/main2.css">
   
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet" />
  <script type="module" src="/js/main.js"></script>
	<?php wp_head(); ?>
  <style>
/* ============================================
   SKELETON LOADER
   ============================================ */

/* Базовый скелетон-элемент с эффектом мерцания */
.skeleton-card {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  padding: 0 0 20px 0;
  /* box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04); */
  animation: skeleton-fade-in 0.3s ease;
}

.skeleton-image {
  width: 100%;
  aspect-ratio: 1 / 1;
  background: #EEEDF199;
  border-radius: 24px;
  margin-bottom: 20px;
  position: relative;
  overflow: hidden;
}

.skeleton-line {
  height: 16px;
  background: #EEEDF199;
  border-radius: 12px;
  margin: 0 20px 10px 0px;
  position: relative;
  overflow: hidden;
}

.skeleton-line.title {
  height: 20px;
  width: 70%;
  margin-bottom: 8px;
}

.skeleton-line.short {
  width: 45%;
}

.skeleton-line.medium {
  width: 85%;
}

/* Эффект "шиммера" (бегущий блик) */
.skeleton-image::after,
.skeleton-line::after {
  content: '';
  position: absolute;
  top: 0;
  left: -150%;
  width: 150%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent 0%,
    rgba(255, 255, 255, 0.7) 50%,
    transparent 100%
  );
  animation: skeleton-shimmer 1.4s infinite;
}

@keyframes skeleton-shimmer {
  0%   { left: -150%; }
  100% { left: 150%; }
}

@keyframes skeleton-fade-in {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Сетка скелетонов повторяет сетку cards-grid */
.cards-grid .skeleton-card {
  display: block;
}

/* Инлайновый скелетон для AI-блока */
.ai-skeleton-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
  width: 100%;
}  
/* Подсказка поиска */
.search-hint {
  grid-column: 1 / -1;
  text-align: center;
  color: #999;
  font-size: 16px;
  padding: 40px 20px;
  margin: 0;
}

/* Скрыть старые спиннеры (на всякий случай) */
.loading-spinner { display: none !important; } 
  </style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="header-wrap">
      <div class="header-logo">
        <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="Logo memory lab" /></a>
      </div>
   <div class="header-menu">
    <?php
    $menu_items = wp_get_nav_menu_items('main');

    if ($menu_items) : ?>
        <ul class="header-menu-list">
            <?php foreach ($menu_items as $item) : ?>
                <li>
                    <a href="<?php echo esc_url($item->url); ?>">
                        <?php echo esc_html($item->title); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
      <div class="header-social">
        <div class="header-social_wrap">
          <div class="header-tel">
            <div class="header-tel_wrap">
              <div class="header-tel_item">
                <a class="header-tel-a" href="tel:+375298210398">+375 29 821 03 98</a>
              </div>
            </div>
          </div>
          <div class="header-social_item soc-hover">
            <a href="https://t.me/alexeueasy" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="telegram" /></a>
          </div>
          <div class="header-social_item soc-hover">
            <a href="viber://chat?number=375298210398" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt="telegram" /></a>
          </div>
          <div class="header-social_item soc-hover">
            <a href="https://wa.me/375298210398?" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/whatsapp.svg" alt="WhatsApp" /></a>
          </div>
          <div class="header-social_item soc-hover">
            <a href="https://www.instagram.com/memorylab.by?igsh=MWQ0dGtsZXlqMGc5cA%3D%3D&utm_source=qr"><img src="<?php echo get_template_directory_uri(); ?>/images/instagram.svg" alt="instagram" /></a>
          </div>
        </div>
      </div>
      <div class="mob-burger">
        <button id="burger-menu" class="mm-burger-btn">
          <img src="<?php echo get_template_directory_uri(); ?>/images/burger.svg" alt="">
        </button>
      </div>
    </div>
  </header>


   <div class="mm-overlay"></div>
<!-- mobile menu -->
<div class="mm-sidebar">
  <div class="mm-sidebar-header">
    <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="Memory Lab" class="mm-logo" /></a>
    <button class="mm-close-btn"><img src="<?php echo get_template_directory_uri(); ?>/images/close.svg" alt=""></button>
  </div>

  <?php
  $mobile_menu_items = wp_get_nav_menu_items('main');

  if ($mobile_menu_items) : ?>
      <nav class="mm-nav">
          <?php foreach ($mobile_menu_items as $item) : ?>
              <a href="<?php echo esc_url($item->url); ?>" class="mm-nav-item">
                  <?php echo esc_html($item->title); ?>
              </a>
          <?php endforeach; ?>
      </nav>
  <?php endif; ?>

  <div class="mm-contact-section">
    <div class="mm-contact-card">
      <div class="mm-contact-name">Алексей</div>
      <div class="mm-contact-phone"><a href="tel:+375298210398">+375 29 821 03 98</a></div>
      <div class="mm-contact-icons">
        <a href="https://t.me/alexeueasy" target="_blank" class="mm-icon-btn soc-hover"><img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="Telegram" /></a>
        <a href="viber://chat?number=375298210398" target="_blank" class="mm-icon-btn soc-hover"><img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt="Viber" /></a>
        <a href="https://wa.me/375298210398?" target="_blank" class="mm-icon-btn soc-hover"><img src="<?php echo get_template_directory_uri(); ?>/images/whatsapp.svg" alt="WhatsApp" /></a>
        <a href="https://www.instagram.com/memorylab.by?igsh=MWQ0dGtsZXlqMGc5cA%3D%3D&utm_source=qr" target="_blank" class="mm-icon-btn soc-hover"><img src="<?php echo get_template_directory_uri(); ?>/images/instagram.svg" alt="instagram" /></a>
      </div>
    </div>
  </div>
</div>