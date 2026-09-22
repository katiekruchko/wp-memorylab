<?php
/**
 * Секция "Примеры обработки ИИ" для single-staff страницы
 */

// Получаем текущую запись
$pod = pods(get_post_type(), get_the_ID());

// Получаем тексты описаний (repeatable)
$ai_texts = array_filter((array) $pod->field('ai-primer-text'), function($item) {
    if (is_array($item)) {
        foreach ($item as $value) {
            if (is_string($value) && !empty(trim(strip_tags($value)))) {
                return true;
            }
        }
        return false;
    }
    return is_string($item) && !empty(trim(strip_tags($item)));
});

// Получаем изображения (repeatable)
$ai_fotos = array_filter((array) $pod->field('ai_fotos'), function($item) {
    // Может быть ID, массив или объект
    if (is_array($item)) {
        return !empty($item['ID']) || !empty($item['guid']);
    }
    return !empty($item);
});

// Переиндексация
$ai_texts = array_values($ai_texts);
$ai_fotos = array_values($ai_fotos);

// Выводим блок только если есть картинки
if (!empty($ai_fotos)):

    // Собираем URL-ы картинок
    $image_urls = [];
    foreach ($ai_fotos as $foto) {
        $url = '';
        if (is_array($foto)) {
            if (!empty($foto['ID'])) {
                $url = pods_image_url($foto['ID'], 'large');
            } elseif (!empty($foto['guid'])) {
                $url = $foto['guid'];
            }
        } elseif (is_numeric($foto)) {
            $url = pods_image_url($foto, 'large');
        } elseif (is_string($foto)) {
            $url = $foto;
        }
        if (!empty($url)) {
            $image_urls[] = $url;
        }
    }

    // Разбиваем картинки по парам (до / после)
    $image_pairs = array_chunk($image_urls, 2);

    // Текст описания — берём первый или объединяем
    $desc_text = '';
    if (!empty($ai_texts)) {
        $first = $ai_texts[0];
        if (is_array($first)) {
            $desc_text = reset($first);
        } else {
            $desc_text = $first;
        }
    }
    ?>

    <section class="eg-ff gap-section">
        <h2 class="h2-catalog">Примеры <span class="text-gradient">обработки ИИ</span></h2>

        <div class="eg-ff-text-block">
            <?php if (!empty($desc_text)): ?>
                <div class="eg-ff-desc"><?php echo wp_kses_post($desc_text); ?></div>
            <?php endif; ?>

            <div class="eg-ff-btn-block">
                <div class="">
                    <a href="/prompts" class="btn-grey btn-more btn-grey-and-arr">
                        <span>Перейти в библиотеку</span>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/next-btn-icon.svg'); ?>" alt=" ">
                    </a>
                </div>
            </div>
        </div>

        <div class="img-gen-eg">
            <div class="img-gen-eg_wrap">
                <div class="img-gen-eg_wrap_inner">
                    <?php foreach ($image_pairs as $pair): ?>
                        <div class="img-gen-eg_col">
                            <div class="img-gen-eg_col-item">
                                <img class="img-gen_after-icon"
                                     src="<?php echo esc_url(get_template_directory_uri() . '/images/after-eg-icon.svg'); ?>"
                                     alt="">
                                <?php foreach ($pair as $img_url): ?>
                                    <img src="<?php echo esc_url($img_url); ?>" alt=" ">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

<?php endif;