<?php
/**
 * For single-staff page, section mehanika
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */

// Получаем текущую запись
$pod = pods(get_post_type(), get_the_ID());

// Получаем и фильтруем шаги одним махом
$mehanika_steps = array_filter((array)$pod->field('mehanika-raboty'), function($step) {
    if (is_array($step)) {
        // Проверяем наличие контента в массиве
        foreach ($step as $value) {
            if (is_string($value) && !empty(trim(strip_tags($value)))) {
                return true; // Сохраняем этот шаг
            }
        }
        return false; // Пропускаем пустой массив
    }
    
    if (is_string($step) && !empty(trim(strip_tags($step)))) {
        return true; // Сохраняем непустую строку
    }
    
    return false; // Пропускаем все остальное
});

// Выводим блок только если есть непустые шаги
if (!empty($mehanika_steps)): 
    
    // Изображение
    $mehanika_image = $pod->field('mehanika-image');
    $image_url = pods_image_url($mehanika_image, 'large') ?: 
                 get_template_directory_uri() . '/images/bg-abstract.png';
    ?>
    
    <section class="mehanika-wrap">
        <h2 class="h2-catalog">
            <span class="text-gradient">Механика</span> работы
        </h2>
        
        <div class="steps-wrap">
            <?php foreach (array_values($mehanika_steps) as $index => $step): // array_values для сброса ключей ?>
                <div class="step">
                    <div class="step-number"><?php echo $index + 1; ?></div>
                    <div class="step-content">
                        <?php 
                        // Извлекаем контент для отображения
                        $display_content = '';
                        
                        if (is_array($step)) {
                            // Приоритетные поля для контента
                            $content_fields = ['content', 'text', 'post_content', 'description'];
                            foreach ($content_fields as $field) {
                                if (isset($step[$field]) && is_string($step[$field]) && !empty(trim($step[$field]))) {
                                    $display_content = $step[$field];
                                    break;
                                }
                            }
                            
                            // Если не нашли в приоритетных полях, ищем любое текстовое поле
                            if (empty($display_content)) {
                                foreach ($step as $value) {
                                    if (is_string($value) && !empty(trim(strip_tags($value)))) {
                                        $display_content = $value;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $display_content = (string)$step;
                        }
                        
                        echo wp_kses_post($display_content);
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="steps-image">
            <img src="<?php echo esc_url($image_url); ?>" 
                 alt="Механика работы" 
                 class="steps-image-img"
                 loading="lazy">
        </div>
    </section>
    
<?php endif; ?>
