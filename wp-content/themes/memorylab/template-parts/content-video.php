<?php
/**
 * Секция "Примеры ИИ-видео" для single-staff страницы
 * 4 блока: фото+фото -> видео
 */

$pod = pods(get_post_type(), get_the_ID());

// Текст описания (обычное поле)
$vd_text = $pod->display('ai-text-video-1');

// Собираем все 4 блока
$vd_blocks = [];

for ($i = 1; $i <= 4; $i++) {
    // Фото + фото (repeatable, ожидаем 2 картинки)
    $foto_items = array_filter((array) $pod->field("ai-foto-plus-foto-{$i}"), function($item) {
        return !empty($item);
    });
    $foto_items = array_values($foto_items);

    // Ссылка на видео
    $video_link = trim((string) $pod->field("ai-videolink-{$i}"));

    // Обложка видео
    $cover_item = $pod->field("ai-cover-{$i}");
    $cover_url  = '';

    if (is_array($cover_item)) {
        if (!empty($cover_item['ID'])) {
            $cover_url = pods_image_url($cover_item['ID'], 'large');
        } elseif (!empty($cover_item['guid'])) {
            $cover_url = $cover_item['guid'];
        }
    } elseif (is_numeric($cover_item)) {
        $cover_url = pods_image_url($cover_item, 'large');
    } elseif (is_string($cover_item)) {
        $cover_url = $cover_item;
    }

    // Собираем URL-ы фото+фото
    $foto_urls = [];
    foreach ($foto_items as $item) {
        $url = '';
        if (is_array($item)) {
            $url = !empty($item['ID']) ? pods_image_url($item['ID'], 'large') : ($item['guid'] ?? '');
        } elseif (is_numeric($item)) {
            $url = pods_image_url($item, 'large');
        } elseif (is_string($item)) {
            $url = $item;
        }
        if (!empty($url)) $foto_urls[] = $url;
    }

    // Блок выводим только если есть обе картинки, ссылка и обложка
    if (count($foto_urls) >= 2 && !empty($video_link) && !empty($cover_url)) {
        $vd_blocks[] = [
            'foto_1'    => $foto_urls[0],
            'foto_2'    => $foto_urls[1],
            'video'     => $video_link,
            'cover'     => $cover_url,
        ];
    }
}

// Если ни одного блока — секцию не выводим
if (!empty($vd_blocks)):

    // Преобразуем ссылку YouTube в embed-формат
    $to_embed = function($url) {
        $url = trim($url);
        if (preg_match('~youtube\.com/watch\?v=([^\&]+)~i', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=0&controls=1&rel=0&modestbranding=1&playsinline=1&enablejsapi=1';
        }
        if (preg_match('~youtu\.be/([^\?]+)~i', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=0&controls=1&rel=0&modestbranding=1&playsinline=1&enablejsapi=1';
        }
        if (preg_match('~youtube\.com/embed/([^\?]+)~i', $url, $m)) {
            return $url; // уже embed
        }
        if (preg_match('~vimeo\.com/(\d+)~i', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }
        return $url;
    };

    $theme_uri = get_template_directory_uri();
    ?>

    <section class="eg-vd gap-section">
        <h2 class="h2-catalog">Примеры <span class="text-gradient">ИИ-видео</span></h2>

        <?php if (!empty($vd_text)): ?>
            <div class="eg-ff-text-block">
                <div class="eg-ff-desc"><?php echo $vd_text; ?></div>
            </div>
        <?php endif; ?>

        <?php foreach ($vd_blocks as $block): ?>
            <div class="vd-gen-eg">
                <div class="vd-gen-eg_wrap">
                    <div class="vd-gen-eg_wrap_inner">

                        <div class="vd-gen-eg_col">
                            <div class="vd-gen-eg_col-item">
                                <img class="vd-gen_plus-icon"
                                     src="<?php echo esc_url($theme_uri . '/images/plus-eg-icon.svg'); ?>"
                                     alt=" ">
                                <img class="vd-gen-img"
                                     src="<?php echo esc_url($block['foto_1']); ?>"
                                     alt=" ">
                                <img class="vd-gen-img"
                                     src="<?php echo esc_url($block['foto_2']); ?>"
                                     alt=" ">
                            </div>
                        </div>

                        <div class="vd-gen-eg_col">
                            <div class="vd-gen-eg_col-item vd-gen-eg_col-item_video">
                                <div class="eg-vd-preview-wrapper">
                                    <img class="vd-gen-eg_next-icon"
                                         src="<?php echo esc_url($theme_uri . '/images/after-eg-icon.svg'); ?>"
                                         alt=" ">
                                    <img class="vd-gen_after-icon egVdPlayBtn"
                                         src="<?php echo esc_url($theme_uri . '/images/play-eg-icon.svg'); ?>"
                                         alt="Play video">
                                    <img class="egVdPreviewImg vd-gen-img"
                                         src="<?php echo esc_url($block['cover']); ?>"
                                         alt="Video preview">
                                </div>
                                <div class="eg-vd-video-iframe-wrapper">
    <iframe
        class="eg-vd-youtube-iframe"
        src="<?php echo esc_url($to_embed($block['video'])); ?>"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
        allowfullscreen
        loading="lazy">
    </iframe>
</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

<?php endif; ?>