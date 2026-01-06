<?php
/**
 * For single-staff page, section rent
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */


// Получаем текущую запись
$pod = pods(get_post_type(), get_the_ID());

// Получаем и фильтруем поле 'arenda' за один проход
$arenda_items = array_filter((array)$pod->field('arenda'), function($item) {
    // Проверяем наличие любого текстового контента
    $content = '';
    
    if (is_array($item)) {
        // Проверяем все строковые поля в массиве
        foreach ($item as $value) {
            if (is_string($value) && !empty(trim(strip_tags($value)))) {
                return true;
            }
        }
        return false;
    }
    
    if (is_string($item) && !empty(trim(strip_tags($item)))) {
        return true;
    }
    
    return false;
});

// Если после фильтрации массив пустой - не выводим section
if (empty($arenda_items)) {
    return;
}
?>

<section class="rent-wrap gap-section">
    <h2 class="h2-catalog">
        Что входит в <span class="text-gradient">аренду</span>
    </h2>
    
    <div class="wrap-tiles">
        <?php foreach (array_values($arenda_items) as $index => $item): ?>
            <?php 
            // Извлекаем контент для парсинга
            $content_to_parse = '';
            
            if (is_array($item)) {
                // Приоритетные поля для контента
                $content_fields = ['content', 'text', 'post_content'];
                foreach ($content_fields as $field) {
                    if (isset($item[$field]) && is_string($item[$field]) && !empty(trim($item[$field]))) {
                        $content_to_parse = $item[$field];
                        break;
                    }
                }
                
                // Если не нашли в приоритетных, берем первое непустое строковое поле
                if (empty($content_to_parse)) {
                    foreach ($item as $value) {
                        if (is_string($value) && !empty(trim(strip_tags($value)))) {
                            $content_to_parse = $value;
                            break;
                        }
                    }
                }
            } else {
                $content_to_parse = (string)$item;
            }
            
            // Парсим контент
            $title = '';
            $text = '';
            
            // Ищем h3
            if (preg_match('/<h3[^>]*>(.*?)<\/h3>/i', $content_to_parse, $h3_matches)) {
                $title = trim(strip_tags($h3_matches[1]));
                $content_to_parse = str_replace($h3_matches[0], '', $content_to_parse);
            }
            
            // Получаем оставшийся текст
            $text = trim(strip_tags($content_to_parse));
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            ?>
            
            <?php if (!empty($title) || !empty($text)): ?>
                <div class="tile-item">
                    <div class="tile-num"><?php echo $index + 1; ?></div>
                    <div class="tile-content">
                        <?php if (!empty($title)): ?>
                            <h3 class="tile-h3"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($text)): ?>
                            <div class="tile-text"><?php echo esc_html($text); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
