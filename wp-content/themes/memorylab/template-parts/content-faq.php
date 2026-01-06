<?php
/**
 * For single-staff page, section faq
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */

$pod = pods(get_post_type(), get_the_ID());

// Фильтруем FAQ элементы за один проход
$faq_items = array_filter((array)$pod->field('faq'), function($item) {
    // Проверяем наличие контента
    $content = '';
    
    if (is_array($item)) {
        foreach ($item as $value) {
            if (is_string($value) && !empty(trim(strip_tags($value)))) {
                return true; // Есть контент
            }
        }
        return false;
    }
    
    return is_string($item) && !empty(trim(strip_tags($item)));
});

// Если нет элементов - не выводим
if (empty($faq_items)) {
    return;
}

// Парсим элементы
$parsed_faqs = [];

foreach (array_values($faq_items) as $index => $item) {
    // Извлекаем контент для парсинга
    $content = '';
    
    if (is_array($item)) {
        $fields = ['content', 'text', 'post_content', 'description'];
        foreach ($fields as $field) {
            if (isset($item[$field]) && !empty(trim($item[$field]))) {
                $content = $item[$field];
                break;
            }
        }
    } else {
        $content = (string)$item;
    }
    
    // Парсим
    $question = '';
    $answer = '';
    
    if (preg_match('/<h3[^>]*>(.*?)<\/h3>/i', $content, $h3_match)) {
        $question = trim(strip_tags($h3_match[1]));
        $content = str_replace($h3_match[0], '', $content);
    }
    
    $answer = trim(strip_tags($content));
    $answer = preg_replace('/\s+/', ' ', $answer);
    $answer = trim($answer);
    
    if (!empty($question) && !empty($answer)) {
        $parsed_faqs[] = [
            'question' => $question,
            'answer' => $answer,
            'number' => $index + 1
        ];
    }
}

// Если после парсинга нет элементов - не выводим
if (empty($parsed_faqs)) {
    return;
}
?>

<section class="faq-wrap gap-section">
    <h2 class="h2-catalog">Часто задаваемые <span class="text-gradient">вопросы</span></h2>
    
    <div class="faq-product-wrap">
        <div class="faq-container">
            <?php foreach ($parsed_faqs as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-number"><?php echo $faq['number']; ?></div>
                        <h3><?php echo esc_html($faq['question']); ?></h3>
                        <div class="faq-arrow"></div>
                    </div>
                    <div class="faq-answer">
                        <?php echo esc_html($faq['answer']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>