<?php
/**
 * For single-staff page, section rent
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */

$pod = pods(get_post_type(), get_the_ID());
$fotos = $pod->field('foto-event');

// Получаем URL изображений
$urls = [];
if (is_array($fotos)) {
    foreach ($fotos as $foto) {
        $url = pods_image_url($foto, 'large');
        if ($url) $urls[] = $url;
    }
}

// Если нет фото - не выводим
if (empty($urls)) return;

// Готовим 3 фото
$display_urls = [];
if (count($urls) >= 3) {
    $display_urls = array_slice($urls, 0, 3);
} else {
    // Дублируем чтобы получить 3
    while (count($display_urls) < 3) {
        foreach ($urls as $url) {
            if (count($display_urls) >= 3) break;
            $display_urls[] = $url;
        }
    }
}

$tpl_uri = get_template_directory_uri();
?>

<section class="event-foto-wrap gap-section">
    <h2 class="h2-catalog">Фото с <span class="text-gradient">мероприятий</span></h2>
    
    <div class="efoto-block">
        <div class="efoto-wrap">
            <?php foreach ($display_urls as $img_url): ?>
                <div class="efoto-item">
                    <img src="<?php echo esc_url($img_url); ?>" alt="Фото мероприятия" loading="lazy">
                </div>
            <?php endforeach; ?>
            
            <div class="efoto efoto-insta item">
                <div class="efoto-insta-wrap">
                    <div class="efoto-insta-desktop">
                        <div class="insta-txt insta-txt-1">Больше фото и видео в нашем <span class="text-gradient">инстаграм</span></div>
                        <div class="insta-qr-foto">
                            <img src="<?php echo esc_url($tpl_uri); ?>/images/qrcode-memorylab.svg" alt="QR код" loading="lazy">
                        </div>
                        <div class="insta-txt insta-txt-2"><span class="text-gradient">Подписывайся</span> и следи за обновлениями!</div>
                    </div>
                    <div class="efoto-insta-mobile">
                        <div class="insta-round-logo">
                            <img src="<?php echo esc_url($tpl_uri); ?>/images/logo-round.png" alt="Логотип" loading="lazy">
                        </div>
                        <div class="insta-txt"><span class="text-gradient">Подписывайся</span> и следи за обновлениями!</div>
                        <div class="efoto-insta-btn">
                            <a class="a-color-btn instagram-button btn" href="#">Перейти и подписаться</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>