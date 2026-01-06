<?php
/**
 * For single-staff page, section faq
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */

// Получаем категории текущей записи (поста)
$current_post_id = get_the_ID();
$current_post_type = get_post_type();

// Получаем таксономии текущего поста
// Предполагаем, что у вас есть таксономия 'category' или custom taxonomy
$current_categories = wp_get_post_terms($current_post_id, 'category', array('fields' => 'ids'));

// Если у поста нет категорий, берем все термины из первой доступной таксономии
if (empty($current_categories) || is_wp_error($current_categories)) {
    // Получаем все таксономии для текущего типа записи
    $taxonomies = get_object_taxonomies($current_post_type);
    
    if (!empty($taxonomies)) {
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_post_terms($current_post_id, $taxonomy, array('fields' => 'ids'));
            if (!empty($terms) && !is_wp_error($terms)) {
                $current_categories = $terms;
                break;
            }
        }
    }
}

// Если все равно нет категорий, используем пустой массив
if (empty($current_categories) || is_wp_error($current_categories)) {
    $current_categories = array();
}

// Ищем посты с такими же категориями
$args = array(
    'post_type'      => 'staff', // или ваш тип записи
    'posts_per_page' => 50, // Берем больше, чтобы потом выбрать 4 случайных
    'post_status'    => 'publish',
    'post__not_in'   => array($current_post_id), // Исключаем текущий пост
    'tax_query'      => array(),
    'orderby'        => 'rand', // Сразу сортируем случайно
);

// Добавляем фильтрацию по категориям только если они есть
if (!empty($current_categories)) {
    // Определяем правильную таксономию
    $taxonomy = 'category'; // По умолчанию
    
    // Проверяем, есть ли у типа записи 'staff' таксономия 'category'
    if (!taxonomy_exists('category') || !is_object_in_taxonomy('staff', 'category')) {
        // Ищем другую таксономию
        $staff_taxonomies = get_object_taxonomies('staff');
        if (!empty($staff_taxonomies)) {
            $taxonomy = $staff_taxonomies[0]; // Берем первую доступную
        }
    }
    
    $args['tax_query'] = array(
        array(
            'taxonomy' => $taxonomy,
            'field'    => 'term_id',
            'terms'    => $current_categories,
            'operator' => 'IN'
        )
    );
}

$related_query = new WP_Query($args);

// Если нашлось больше 4 постов, выбираем случайные 4
$related_posts = array();
if ($related_query->have_posts()) {
    $all_posts = $related_query->posts;
    
    // Если постов больше 4, перемешиваем и берем 4
    if (count($all_posts) > 4) {
        shuffle($all_posts);
        $related_posts = array_slice($all_posts, 0, 4);
    } else {
        // Если 4 или меньше, берем все
        $related_posts = $all_posts;
    }
}

// Если нет связанных постов по категориям, показываем просто последние посты
if (empty($related_posts)) {
    $fallback_args = array(
        'post_type'      => 'staff',
        'posts_per_page' => 4,
        'post_status'    => 'publish',
        'post__not_in'   => array($current_post_id),
        'orderby'        => 'rand',
    );
    
    $fallback_query = new WP_Query($fallback_args);
    if ($fallback_query->have_posts()) {
        $related_posts = $fallback_query->posts;
    }
}

// Если есть посты для отображения
if (!empty($related_posts)):
?>

<!-- Карточки -->
<div class="cards-grid">
    <?php
    global $post;
    foreach ($related_posts as $post):
        setup_postdata($post);
        get_template_part('template-parts/content', 'card');
    endforeach;
    wp_reset_postdata();
    ?>
</div>

<?php else: ?>
    <p>Нет связанных интерактивов.</p>
<?php endif; ?>