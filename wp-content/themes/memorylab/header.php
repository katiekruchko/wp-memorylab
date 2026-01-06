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
  <!-- <link rel="stylesheet" href="scss/styles.scss"> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="/scss/index.css">
  <link rel="stylesheet" href="/scss/secondary.css">
  <!-- <link rel="stylesheet" href="scss/main.css"> -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet" />
  <script type="module" src="/js/main.js"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="header-wrap">
      <div class="header-menu">
        <ul class="header-menu-list">
          <li class="a-catalog"><a href="/catalog">Каталог аренды</a></li>
          <li><a href="/blog">Блог</a></li>
          <li><a href="/calculator-ai">ИИ калькулятор</a></li>
          <li><a href="/contact">Контакты</a></li>
        </ul>
      </div>
      <div class="header-logo">
        <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="Logo memory lab" /></a>
      </div>

      <div class="header-social">
        <div class="header-social_wrap">
          <div class="header-tel">
            <div class="header-tel_wrap">
              <div class="header-tel_item">
                <a class="header-tel-a" href="tel:+375292720351">+375 29 272 03 51</a>
              </div>
            </div>
          </div>
          <div class="header-social_item soc-hover">
            <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="telegram" /></a>
          </div>
          <div class="header-social_item soc-hover">
            <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt="telegram" /></a>
          </div>
          <div class="header-social_item soc-hover">
            <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/instagram.svg" alt="telegram" /></a>
          </div>
        </div>
      </div>
      <div class="mob-burger">
        <button id="burger-menu" class="mm-burger-btn">
          <img src="<?php echo get_template_directory_uri(); ?><?php echo get_template_directory_uri(); ?>/images/burger.svg" alt="">
        </button>
      </div>
    </div>
  </header>


   <div class="mm-overlay"></div>

   <!-- mobole menu -->
   <div class="mm-sidebar">
     <div class="mm-sidebar-header">
       <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="Memory Lab" class="mm-logo" /></a>
       <button class="mm-close-btn"><img src="<?php echo get_template_directory_uri(); ?>/images/close.svg" alt=""></button>
     </div>
 
     <nav class="mm-nav">
       <a href="#" class="mm-nav-item mm-nav-item--highlight">Каталог аренды</a>
       <a href="#" class="mm-nav-item">Блог</a>
       <a href="#" class="mm-nav-item">ИИ калькулятор</a>
       <a href="#" class="mm-nav-item">Контакты</a>
     </nav>
     <div class="mm-contact-section">
       <div class="mm-contact-card">
         <div class="mm-contact-name">Алексей</div>
         <div class="mm-contact-phone"><a href="tel:+375298210398">+375 29 821 03 98</a></div>
         <div class="mm-contact-icons">
           <a href="#" class="mm-icon-btn soc-hover"><img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="Telegram" /></a>
           <a href="#" class="mm-icon-btn soc-hover"><img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt="Viber" /></a>
           <a href="#" class="mm-icon-btn soc-hover"><img src="<?php echo get_template_directory_uri(); ?>/images/whatsapp.svg" alt="WhatsApp" /></a>
         </div>
       </div>
     </div>
   </div>

