<?php
/**
 * Карточка товара для каталога
 *
 * @param int $post_id (optional) ID поста. Если не указан, используется текущий пост
 */
if (isset($args) && isset($args['post_id'])) {
    $post_id = $args['post_id'];
} else {
    $post_id = get_the_ID();
}

// Изображение
$image_url = get_the_post_thumbnail_url($post_id, 'medium');
if (!$image_url) {
    $image_url = get_template_directory_uri() . '/images/placeholder.png';
}

// Основные категории (если есть)
$categories = get_the_terms($post_id, 'post_tag');

// Получаем заголовок и описание
$title = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
if (empty($excerpt)) {
    $content = get_the_content(null, false, $post_id);
    $excerpt = wp_trim_words($content, 20, '...');
}
?>

<div class="card">
    <div class="card-image-wrapper">
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="card-image" />
        <div class="badge-wrap">
            <?php if ($categories && !is_wp_error($categories)) : ?>
                <?php foreach ($categories as $category) : ?>
                    <span class="badge <?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-content">
        <h3 class="card-title"><?php echo esc_html($title); ?></h3>
        <div class="card-desc">
            <?php echo esc_html($excerpt); ?>
        </div>
    </div>
</div>
