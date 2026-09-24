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
            <a href="https://t.me/alexeueasy" target="_blank" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/telegram.svg" alt="" /></a>
            <a href="iber://chat?number=375298210398" target="_blank" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/viber.svg" alt="" /></a>
            <a href="https://wa.me/375298210398?" target="_blank" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/whatsapp.svg" alt="" /></a>
            <a href="https://www.instagram.com/memorylab.by" target="_blank" class="footer-soc"><img src="<?php echo get_template_directory_uri(); ?>/images/instagram.svg" alt="" /></a>
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
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script type="module" src="<?php echo get_template_directory_uri(); ?>/js/site.js"></script>


<?php wp_footer(); ?>

</body>
</html>
