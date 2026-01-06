<?php
/**
 * For single-staff page, section rent
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */


// Получаем текущую запись
$pod = pods(get_post_type(), get_the_ID());

// Получаем повторяющееся поле 'additional-text'
$additional_texts_raw = $pod->field('additional-text');

// Получаем массив изображений из поля 'additional-img'
$additional_images = $pod->field('additional-img');

// Фильтруем текстовые элементы, удаляя пустые
$additional_texts = [];

if (is_array($additional_texts_raw)) {
    foreach ($additional_texts_raw as $text_item) {
        $content = '';
        
        if (is_array($text_item)) {
            if (isset($text_item['content']) && is_string($text_item['content'])) {
                $content = $text_item['content'];
            } elseif (isset($text_item['text']) && is_string($text_item['text'])) {
                $content = $text_item['text'];
            }
        } elseif (is_string($text_item)) {
            $content = $text_item;
        }
        
        // Проверяем наличие реального контента
        if (!empty(trim(strip_tags($content)))) {
            $additional_texts[] = $content;
        }
    }
}

// Если нет ни одного текстового элемента - не выводим section
if (empty($additional_texts)) {
    return;
}

// Функция для парсинга HTML контента
function parse_additional_content($html_content) {
    $result = [
        'title' => '',
        'description' => ''
    ];
    
    if (empty($html_content)) {
        return $result;
    }
    
    // Ищем заголовок h3
    if (preg_match('/<h3[^>]*>(.*?)<\/h3>/i', $html_content, $h3_matches)) {
        $result['title'] = trim(strip_tags($h3_matches[1]));
        // Удаляем найденный h3 из текста
        $html_content = str_replace($h3_matches[0], '', $html_content);
    }
    
    // Ищем параграф p
    if (preg_match('/<p[^>]*>(.*?)<\/p>/i', $html_content, $p_matches)) {
        $result['description'] = trim(strip_tags($p_matches[1]));
    } else {
        // Если нет тега p, берем весь оставшийся текст
        $result['description'] = trim(strip_tags($html_content));
    }
    
    // Очищаем описание
    $result['description'] = preg_replace('/\s+/', ' ', $result['description']);
    $result['description'] = trim($result['description']);
    
    return $result;
}

// Получаем URL изображений
$image_urls = [];

if (is_array($additional_images)) {
    foreach ($additional_images as $index => $image) {
        $img_url = '';
        
        if (is_array($image) && isset($image['ID'])) {
            $img_url = wp_get_attachment_image_url($image['ID'], 'large');
        } elseif (is_numeric($image)) {
            $img_url = wp_get_attachment_image_url($image, 'large');
        } elseif (is_array($image) && isset($image['guid'])) {
            $img_url = $image['guid'];
        } elseif (is_string($image)) {
            $img_url = $image;
        }
        
        // Fallback на pods_image_url
        if (empty($img_url)) {
            $img_url = pods_image_url($image, 'large');
        }
        
        if (!empty($img_url)) {
            $image_urls[] = $img_url;
        }
    }
}
?>

<section class="addition gap-section">
    <h2 class="h2-catalog">
        <span class="text-gradient">Дополнительные</span> услуги
    </h2>
    
    <div class="addition-block">
        <?php foreach ($additional_texts as $index => $text_content): ?>
            <?php 
            $parsed = parse_additional_content($text_content);
            
            // Пропускаем если нет ни заголовка ни описания
            if (empty($parsed['title']) && empty($parsed['description'])) {
                continue;
            }
            
            // Определяем чередование (odd/even)
            $is_odd = ($index % 2 == 0);
            $item_class = $is_odd ? 'addition-odd' : 'addition-even';
            
            // Получаем URL изображения для этого элемента
            $image_url = isset($image_urls[$index]) ? $image_urls[$index] : '';
            
            // Если нет изображения, используем placeholder
            if (empty($image_url)) {
                $image_url = get_template_directory_uri() . '/images/dop-usluga' . (($index % 2) + 1) . '.jpg';
            }
            
            $item_number = $index + 1;
            ?>
            
            <div class="addition-item <?php echo $item_class; ?>">
                <?php if ($is_odd): ?>
                    <!-- Нечетные элементы: изображение слева -->
                    <div class="addition-image-wrap">
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr($parsed['title'] ?: 'Дополнительная услуга'); ?>" 
                             class="service-image"
                             loading="lazy">
                        <div class="addition-number"><?php echo $item_number; ?></div>
                    </div>
                    <div class="addition-content">
                        <?php if (!empty($parsed['title'])): ?>
                            <h3 class="addition-h interactive-h3"><?php echo esc_html($parsed['title']); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($parsed['description'])): ?>
                            <p class="addition-description"><?php echo esc_html($parsed['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Четные элементы: изображение справа -->
                    <div class="addition-content">
                        <?php if (!empty($parsed['title'])): ?>
                            <h3 class="addition-h interactive-h3"><?php echo esc_html($parsed['title']); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($parsed['description'])): ?>
                            <p class="addition-description"><?php echo esc_html($parsed['description']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="addition-image-wrap">
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr($parsed['title'] ?: 'Дополнительная услуга'); ?>" 
                             class="service-image"
                             loading="lazy">
                        <div class="addition-number"><?php echo $item_number; ?></div>
                    </div>
                <?php endif; ?>
            </div>
            
        <?php endforeach; ?>
    </div>
</section>