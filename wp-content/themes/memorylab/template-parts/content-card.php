<?php
/**
 * Карточка товара для каталога
 */
$post_id = get_the_ID();

// Если используете Pods для поля цены
if (function_exists('pods')) {
    $pod = pods('staff', $post_id);
    if ($pod && $pod->exists()) {
        $price = $pod->field('price');
    }
}

// Изображение
$image_url = get_the_post_thumbnail_url($post_id, 'medium');
if (!$image_url) {
    $image_url = get_template_directory_uri() . '/images/placeholder.png';
}

// Основные категории (если есть)
$categories = get_the_terms($post_id, 'post_tag');
?>

<div class="card">
    <div class="card-image-wrapper">
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>" class="card-image" />
        <div class="badge-wrap">
            <?php if ($categories && !is_wp_error($categories)) : ?>
                <?php foreach ($categories as $category) : ?>
                    <span class="badge <?php print $category->slug; ?> ?>"><?php echo esc_html($category->name); ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-content">
        <h3 class="card-title"><?php the_title(); ?></h3>
        <div class="card-desc">
            <?php
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $content = get_the_content();
                $excerpt = wp_trim_words($content, 20, '...');
            }
            echo esc_html($excerpt);
            ?>
        </div>
        <div class="card-meta">
            <?php if ($price) : ?>
                <div class="card-price"><?php echo esc_html($price); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
